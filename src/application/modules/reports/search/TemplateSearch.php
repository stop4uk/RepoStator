<?php

namespace app\modules\reports\search;

use app\helpers\CommonHelper;
use app\modules\reports\{helpers\TemplateHelper,
    repositories\ConstantRepository,
    repositories\ConstantruleRepository,
    repositories\ReportRepository,
    repositories\TemplateRepository,
    traits\CleanDataProviderByRoleTrait};
use app\modules\users\{components\rbac\items\Permissions, components\rbac\RbacHelper};
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * @property string|null $name
 * @property int|null $report_id
 * @property int|null $use_appg
 * @property int|null $use_grouptype
 * @property int|null $form_type
 * @property int|null $form_userjobs
 * @property string|null $hasConstant
 * @property string|null $hasConstantRule
 * @property int|null $hasGroup
 *
 * @property-read bool $onlyActive
 * @property-read array $groups
 * @property-read array $reports
 * @property-read array $constants
 * @property-read array $constantsRule
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\search
 */
final class TemplateSearch extends Model
{
    use CleanDataProviderByRoleTrait;

    public $name;
    public $report_id;
    public $use_appg;
    public $use_grouptype;
    public $form_type;
    public $form_usejobs;
    public $hasConstant;
    public $hasConstantRule;
    public $hasGroup;

    public readonly bool $onlyActive;
    public readonly array $groups;
    public readonly array $reports;
    public readonly array $constants;
    public readonly array $constantsRule;

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
        $this->constants = ConstantRepository::getAllow(
            reports: $this->reports,
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->constantsRule = ConstantruleRepository::getAllow(
            reports: $this->reports,
            groups: $this->groups,
            active: $this->onlyActive
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['name', 'string', 'length' => [4,64]],
            [['report_id', 'use_appg', 'use_grouptype', 'form_type', 'form_usejobs'], 'integer'],
            [['hasConstant', 'hasConstantRule'], 'string', 'length' => [2,32]],
            ['hasGroup', 'integer'],
            ['hasGroup', 'in', 'range' => array_keys($this->groups)],
            ['report_id', 'in', 'range' => array_keys($this->reports)],
            ['hasConstant', 'in', 'range' => array_keys($this->constants)],
            ['hasConstantRule', 'in', 'range' => array_keys($this->constantsRule)]
        ];
    }

    public function attributeLabels(): array
    {
        return TemplateHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = TemplateRepository::getAllow(
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
            ->andFilterWhere(['=', 'use_appg', CommonHelper::getFilterReplace($this->use_appg)])
            ->andFilterWhere(['=', 'use_grouptype', CommonHelper::getFilterReplace($this->use_grouptype)])
            ->andFilterWhere(['=', 'form_type', CommonHelper::getFilterReplace($this->form_type)])
            ->andFilterWhere(['=', 'form_usejobs', CommonHelper::getFilterReplace($this->form_usejobs)]);

        if ($this->hasGroup) {
            $query->andFilterWhere([
                'or',
                ['REGEXP', 'table_rows', '\b' . $this->hasGroup . '\b'],
                ['REGEXP', 'table_columns', '\b' . $this->hasGroup . '\b']
            ]);
        }

        if ($this->hasConstant) {
            $query->andFilterWhere([
                'or',
                ['REGEXP', 'table_rows', '\b' . $this->hasConstant . '\b'],
                ['REGEXP', 'table_columns', '\b' . $this->hasConstant . '\b']
            ]);
        }

        if ($this->hasConstantRule) {
            $query->andFilterWhere([
                'or',
                ['REGEXP', 'table_rows', '\b' . $this->hasConstantRule . '\b'],
                ['REGEXP', 'table_columns', '\b' . $this->hasConstantRule . '\b']
            ]);
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