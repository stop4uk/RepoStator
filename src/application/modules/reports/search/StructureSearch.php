<?php

namespace app\modules\reports\search;

use app\helpers\CommonHelper;
use app\modules\reports\{helpers\StructureHelper,
    repositories\ReportRepository,
    repositories\StructureRepository,
    traits\CleanDataProviderByRoleTrait};
use app\modules\users\{components\rbac\items\Permissions, components\rbac\RbacHelper};
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * @property string|null $name
 * @property int|null $report_id
 * @property int|null $use_union_rules
 * @property int|null $hasGroup
 *
 * @property-read bool $onlyActive
 * @property-read array $groups
 * @property-read array $reports
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\search
 */
final class StructureSearch extends Model
{
    use CleanDataProviderByRoleTrait;

    public $name;
    public $report_id;
    public $use_union_rules;
    public $hasGroup;

    public readonly bool $onlyActive;
    public readonly array $groups;
    public readonly array $reports;

    public function __construct($config = [])
    {
        $this->onlyActive = RbacHelper::getOnlyActiveRecordsState([
            Permissions::STRUCTURE_VIEW_DELETE_MAIN,
            Permissions::STRUCTURE_VIEW_DELETE_GROUP,
            Permissions::STRUCTURE_VIEW_DELETE_ALL
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray('structure.list.all');
        $this->reports = ReportRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['name', 'string', 'length' => [4,64]],
            [['report_id', 'hasGroup', 'use_union_rules'], 'integer'],
            ['report_id', 'in', 'range' => array_keys($this->reports)],
            ['hasGroup', 'in', 'range' => array_keys($this->groups)],
        ];
    }

    public function attributeLabels(): array
    {
        return StructureHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = StructureRepository::getAllow(
            reports: $this->reports,
            groups: $this->groups,
            active: $this->onlyActive,
            asQuery: true
        )->with(['report']);

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

        $query->andFilterWhere(['=', 'report_id', $this->report_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'use_union_rules', CommonHelper::getFilterReplace($this->use_union_rules)]);


        if ($this->hasGroup) {
            $query->andFilterWhere(['REGEXP', 'groups_only', '\b' . $this->hasGroup . '\b']);
        }

        return $this->cleanData($dataProvider);
    }

    private function cleanData($dataProvider): ActiveDataProvider
    {
        return $this->cleanDataProvider(
            dataProvider: $dataProvider,
            allDeleteRole: Permissions::STRUCTURE_VIEW_DELETE_ALL,
            groupDeleteRole: Permissions::STRUCTURE_VIEW_DELETE_GROUP,
            mainDeleteRole: Permissions::STRUCTURE_VIEW_DELETE_MAIN,
            allListRole: Permissions::STRUCTURE_LIST_ALL,
            groupListRole: Permissions::STRUCTURE_LIST_GROUP,
            mainListRole: Permissions::STRUCTURE_LIST_MAIN
        );
    }
}