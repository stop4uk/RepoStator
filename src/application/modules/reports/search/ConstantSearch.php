<?php

namespace search;

use app\helpers\{HtmlPurifier, RbacHelper,};
use app\traits\CleanDataProviderByRoleTrait;
use ConstantHelper;
use repositories\{ReportBaseRepository};
use repositories\ConstantBaseRepository;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * @property string|null $record
 * @property string|null $name
 * @property string|null $name_full
 * @property string|null $union_rules
 * @property int|null $limitReport
 *
 * @property-read bool $onlyActive
 * @property-read array $groups
 * @property-read array $reports
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\search\report
 */
final class ConstantSearch extends Model
{
    use CleanDataProviderByRoleTrait;

    public $record;
    public $name;
    public $name_full;
    public $union_rules;
    public $limitReport;

    public readonly bool $onlyActive;
    public readonly array $groups;
    public readonly array $reports;

    public function __construct($config = [])
    {
        $this->onlyActive = RbacHelper::getOnlyActiveRecordsState([
            'constant.view.delete.main',
            'constant.view.delete.group',
            'constant.view.delete.all'
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray('constant.list.all');
        $this->reports = ReportBaseRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['record', 'string', 'length' => [2,32]],
            ['name', 'string', 'length' => [2,64]],
            ['name_full', 'string'],
            ['union_rules', 'string', 'length' => [4,128]],
            ['limitReport', 'integer'],
            ['limitReport', 'in', 'range' => array_keys($this->reports)],
            [['record', 'name', 'name_full', 'union_rules'], 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)]
        ];
    }

    public function attributeLabels(): array
    {
        return ConstantHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = ConstantBaseRepository::getAllow(
            reports: $this->reports,
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

        if ( !($this->load($params) && $this->validate()) ) {
            return $this->cleanData($dataProvider);
        }

        $query->andFilterWhere(['like', 'record', $this->record])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_full', $this->name_full])
            ->andFilterWhere(['like', 'union_rules', $this->union_rules]);

        if ( $this->limitReport ) {
            $query->andFilterWhere(['REGEXP', 'reports_only', '\b' . $this->limitReport . '\b']);
        }

        return $this->cleanData($dataProvider);
    }

    private function cleanData($dataProvider): ActiveDataProvider
    {
        return $this->cleanDataProvider(
            dataProvider: $dataProvider,
            allDeleteRole: 'constant.view.delete.all',
            groupDeleteRole: 'constant.view.delete.group',
            mainDeleteRole: 'constant.view.delete.main',
            allListRole: 'constant.list.all',
            groupListRole: 'constant.list.group',
            mainListRole: 'constant.list.main'
        );
    }
}
