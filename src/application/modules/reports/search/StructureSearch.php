<?php

namespace search;

use app\helpers\{CommonHelper, RbacHelper};
use app\traits\CleanDataProviderByRoleTrait;
use repositories\{StructureBaseRepository};
use repositories\ReportBaseRepository;
use StructureHelper;
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
 * @package app\search\report
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
            'structure.view.delete.main',
            'structure.view.delete.group',
            'structure.view.delete.all'
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray('structure.list.all');
        $this->reports = ReportBaseRepository::getAllow(
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
        $query = StructureBaseRepository::getAllow(
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

        if ( !($this->load($params) && $this->validate()) ) {
            return $this->cleanData($dataProvider);
        }

        $query->andFilterWhere(['=', 'report_id', $this->report_id])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['=', 'use_union_rules', CommonHelper::getFilterReplace($this->use_union_rules)]);


        if ( $this->hasGroup ) {
            $query->andFilterWhere(['REGEXP', 'groups_only', '\b' . $this->hasGroup . '\b']);
        }

        return $this->cleanData($dataProvider);
    }

    private function cleanData($dataProvider): ActiveDataProvider
    {
        return $this->cleanDataProvider(
            dataProvider: $dataProvider,
            allDeleteRole: 'structure.view.delete.all',
            groupDeleteRole: 'structure.view.delete.group',
            mainDeleteRole: 'structure.view.delete.main',
            allListRole: 'structure.list.all',
            groupListRole: 'structure.list.group',
            mainListRole: 'structure.list.main'
        );
    }
}