<?php

namespace app\modules\reports\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\helpers\HtmlPurifier;
use app\modules\reports\{
    helpers\ReportHelper,
    repositories\ReportRepository,
    traits\CleanDataProviderByRoleTrait
};
use app\modules\users\{
    components\rbac\items\Permissions,
    components\rbac\RbacHelper
};

/**
 * @property string|null $name
 * @property int|null $left_period
 * @property int|null $block_minutes
 * @property int|null $hasGroupOnly
 * @property int|null $hasGroupRequired
 *
 * @property-read bool $onlyActive
 * @property-read array $groups
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\search
 */
final class ReportSearch extends Model
{
    use CleanDataProviderByRoleTrait;

    public $name;
    public $left_period;
    public $block_minutes;
    public $hasGroupOnly;
    public $hasGroupRequired;
    public $allow_dynamicFormSearch;

    public readonly bool $onlyActive;
    public readonly array $groups;

    public function __construct($config = [])
    {
        $this->onlyActive = RbacHelper::getOnlyActiveRecordsState([
            Permissions::REPORT_VIEW_DELETE_MAIN,
            Permissions::REPORT_VIEW_DELETE_GROUP,
            Permissions::REPORT_VIEW_DELETE_ALL
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray('report.list.all');

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['name', 'string', 'length' => [4, 64]],
            ['left_period', 'integer', 'min' => 10],
            ['block_minutes', 'integer', 'min' => 5],
            [['hasGroupOnly', 'hasGroupRequired', 'allow_dynamicFormSearch'], 'integer'],
            [['hasGroupOnly', 'hasGroupRequired'], 'in', 'range' => array_keys($this->groups)],
            ['name', 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return ReportHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = ReportRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive,
            asQuery: true
        );

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
            return $this->cleanData($dataProvider);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'left_period', $this->left_period])
            ->andFilterWhere(['=', 'block_minutes', $this->block_minutes])
            ->andFilterWhere(['=', 'allow_dynamicForm', $this->allow_dynamicFormSearch]);

        if ($this->hasGroupOnly) {
            $query->andFilterWhere(['REGEXP', 'groups_only', '\b' . $this->hasGroupOnly . '\b']);
        }

        if ($this->hasGroupRequired) {
            $query->andFilterWhere(['REGEXP', 'groups_required', '\b' . $this->hasGroupRequired . '\b']);
        }

        return $this->cleanData($dataProvider);
    }

    private function cleanData($dataProvider): ActiveDataProvider
    {
        return $this->cleanDataProvider(
            dataProvider: $dataProvider,
            allDeleteRole: Permissions::REPORT_VIEW_DELETE_ALL,
            groupDeleteRole: Permissions::REPORT_VIEW_DELETE_GROUP,
            mainDeleteRole: Permissions::REPORT_VIEW_DELETE_MAIN,
            allListRole: Permissions::REPORT_LIST_ALL,
            groupListRole: Permissions::REPORT_LIST_GROUP,
            mainListRole: Permissions::REPORT_LIST_MAIN
        );
    }
}