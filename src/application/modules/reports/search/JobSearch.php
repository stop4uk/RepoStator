<?php

namespace app\modules\reports\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\helpers\CommonHelper;
use app\modules\reports\{
    entities\ReportFormJobEntity,
    helpers\JobHelper,
    repositories\ReportRepository,
    repositories\TemplateRepository
};
use app\modules\users\components\rbac\RbacHelper;

/**
 * @property int|null $job_status
 * @property int|null $report_id
 * @property int|null $template_id
 * @property string|null $created_at
 * @property int|null $created_gid
 * @property string|null $updated_at
 *
 * @property bool $onlyMine
 * @property-read array $groups
 * @property-read array $reports
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\search
 */
class JobSearch extends Model
{
    public $job_status;
    public $report_id;
    public $template_id;
    public $created_at;
    public $created_gid;
    public $updated_at;

    public bool $onlyMine = true;
    public readonly array $groups;
    public readonly array $reports;
    public readonly array $templates;

    public function __construct($config = [])
    {
        $this->groups = RbacHelper::getAllowGroupsArray('admin.queue.template.all');
        $this->reports = ReportRepository::getAllow(
            groups: $this->groups
        );
        $this->templates = TemplateRepository::getAllow(
            reports: $this->reports,
            groups: $this->groups
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['job_status', 'report_id', 'template_id', 'created_gid'], 'integer'],
            ['job_status', 'in', 'range' => CommonHelper::getFilterReplaceData(ReportFormJobEntity::STATUSES)],

            [['created_at', 'updated_at'], 'string']
        ];
    }

    public function attributeLabels(): array
    {
        return JobHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = ReportFormJobEntity::find()
            ->where(['created_gid' => array_keys($this->groups)])
            ->with(['report', 'template']);

        if (!$this->onlyMine) {
            $query->andFilterWhere(['=', 'created_uid', Yii::$app->getUser()->id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 15
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere(['=', 'job_status', CommonHelper::getFilterReplace($this->job_status)])
            ->andFilterWhere(['=', 'created_gid', $this->created_gid])
            ->andFilterWhere(['=', 'report_id', $this->report_id])
            ->andFilterWhere(['=', 'template_id', $this->template_id]);

        if ($this->created_at) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->created_at));
            $query->andFilterWhere(['BETWEEN', 'created_at', $timePeriod[0], $timePeriod[1]]);
        }

        if ($this->updated_at) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->updated_at));
            $query->andFilterWhere(['BETWEEN', 'updated_at', $timePeriod[0], $timePeriod[1]]);
        }

        return $dataProvider;
    }
}