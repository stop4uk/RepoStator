<?php

namespace app\modules\reports\search;

use app\helpers\HtmlPurifier;
use app\modules\reports\{helpers\ConstantRuleHelper,
    repositories\ConstantRepository,
    repositories\ConstantruleRepository,
    repositories\ReportRepository,
    traits\CleanDataProviderByRoleTrait};
use app\modules\users\{components\rbac\items\Permissions, components\rbac\RbacHelper};
use yii\base\Model;
use yii\data\ActiveDataProvider;

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
 * @package app\modules\reports\search
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
            Permissions::CONSTANTRULE_VIEW_DELETE_MAIN,
            Permissions::CONSTANTRULE_VIEW_DELETE_GROUP,
            Permissions::CONSTANTRULE_VIEW_DELETE_ALL
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray(Permissions::CONSTANTRULE_LIST_ALL);
        $this->reports = ReportRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->constants = ConstantRepository::getAllow(
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
        $query = ConstantruleRepository::getAllow(
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
                'pageSize' => 50
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $this->cleanData($dataProvider);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'record', $this->record])
            ->andFilterWhere(['like', 'rule', $this->hasConstant])
            ->andFilterWhere(['=', 'report_id', $this->limitReport]);

        if ($this->limitReport) {
            $query->andFilterWhere(['REGEXP', 'groups_only', '\b' . $this->limitGroup . '\b']);
        }

        return $this->cleanData($dataProvider);
    }

    private function cleanData($dataProvider): ActiveDataProvider
    {
        return $this->cleanDataProvider(
            dataProvider: $dataProvider,
            allDeleteRole: Permissions::CONSTANTRULE_VIEW_DELETE_ALL,
            groupDeleteRole: Permissions::CONSTANTRULE_VIEW_DELETE_GROUP,
            mainDeleteRole: Permissions::CONSTANTRULE_VIEW_DELETE_MAIN,
            allListRole: Permissions::CONSTANTRULE_LIST_ALL,
            groupListRole: Permissions::CONSTANTRULE_LIST_GROUP,
            mainListRole: Permissions::CONSTANTRULE_LIST_MAIN
        );
    }
}
