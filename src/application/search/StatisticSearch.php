<?php

namespace app\search;

use Yii;
use yii\data\ActiveDataProvider;

use app\entities\report\ReportFormJobEntity;
use app\repositories\report\{
    ReportBaseRepository,
    TemplateBaseRepository
};
use app\helpers\{
    CommonHelper,
    RbacHelper,
    report\JobHelper
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\search
 */
final class StatisticSearch extends Model
{
    public $job_status;
    public $job_id;
    public $report_id;
    public $template_id;

    public readonly array $templates;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->groups = RbacHelper::getAllowGroupsArray('constant.list.all');
        $this->reports = ReportBaseRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->templates = TemplateBaseRepository::getAllow(
            reports: $this->reports,
            groups: $this->groups
        );
    }

    public function rules(): array
    {
        return [
            [['report_id', 'job_status', 'job_id', 'template_id'], 'integer'],
            ['report_id', 'in', 'range' => array_keys($this->reports)],
        ];
    }

    public function attributeLabels(): array
    {
        return JobHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = ReportFormJobEntity::find()
            ->with(['report', 'template'])
            ->where(['created_uid' => Yii::$app->getUser()->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 3
            ],
        ]);

        if ( !($this->load($params) && $this->validate()) ) {
            return $dataProvider;
        }

        $query->andFilterWhere(['=', 'report_id', $this->report_id])
            ->andFilterWhere(['=', 'job_id', $this->job_id])
            ->andFilterWhere(['=', 'template_id', $this->template_id])
            ->andFilterWhere(['=', 'job_status', CommonHelper::getFilterReplace($this->job_status)]);

        return $dataProvider;
    }
}