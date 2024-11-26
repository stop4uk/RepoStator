<?php

namespace app\useCases\reports\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\helpers\HtmlPurifier;
use app\useCases\reports\{
    repositories\ConstantBaseRepository,
    repositories\ConstantruleBaseRepository,
    repositories\ReportBaseRepository,
    helpers\ConstantRuleHelper,
    traits\CleanDataProviderByRoleTrait
};
use app\useCases\users\helpers\RbacHelper;

/**
 * @property string|null $record
 * @property string|null $name;
 * @property string|null $hasConstant
 * @property int|null $limitReport
 * @property int|null $limitGroup
 *
 * @property-read bool $onlyActive
 * @property-read array $groups
 * @property-read array $reports
 * @property-read array $constants
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\search\report
 */
final class ConstantruleSearch extends Model
{
    use CleanDataProviderByRoleTrait;

    public $record;
    public $name;
    public $hasConstant;
    public $limitReport;
    public $limitGroup;

    public readonly bool $onlyActive;
    public readonly array $groups;
    public readonly array $reports;
    public readonly array $constants;

    public function __construct($config = [])
    {
        $this->onlyActive = RbacHelper::getOnlyActiveRecordsState([
            'constantRule.view.delete.main',
            'constantRule.view.delete.group',
            'constantRule.view.delete.all'
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray('constantRule.list.all');
        $this->reports = ReportBaseRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->constants = ConstantBaseRepository::getAllow(
            reports: $this->reports,
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
            ['hasConstant', 'string', 'length' => [2,32]],
            ['hasConstant', 'in', 'range' => array_keys($this->constants)],
            [['limitReport', 'limitGroup'], 'integer'],
            ['limitGroup', 'in', 'range' => array_keys($this->groups)],
            ['limitReport', 'in', 'range' => array_keys($this->reports)],
            [['record', 'name', 'hasConstant'], 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)]
        ];
    }

    public function attributeLabels(): array
    {
        return ConstantRuleHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = ConstantruleBaseRepository::getAllow(
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

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'record', $this->record])
            ->andFilterWhere(['like', 'rule', $this->hasConstant])
            ->andFilterWhere(['=', 'report_id', $this->limitReport]);

        if ( $this->limitReport ) {
            $query->andFilterWhere(['REGEXP', 'groups_only', '\b' . $this->limitGroup . '\b']);
        }

        return $this->cleanData($dataProvider);
    }

    private function cleanData($dataProvider): ActiveDataProvider
    {
        return $this->cleanDataProvider(
            dataProvider: $dataProvider,
            allDeleteRole: 'constantRule.view.delete.all',
            groupDeleteRole: 'constantRule.view.delete.group',
            mainDeleteRole: 'constantRule.view.delete.main',
            allListRole: 'constantRule.list.all',
            groupListRole: 'constantRule.list.group',
            mainListRole: 'constantRule.list.main'
        );
    }
}
