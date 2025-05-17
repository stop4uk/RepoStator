<?php

namespace app\modules\reports\search;

use app\modules\reports\{helpers\DataHelper,
    repositories\ConstantRepository,
    repositories\DataRepository,
    repositories\ReportRepository,
    repositories\StructureRepository,
    traits\CleanDataProviderByRoleTrait};
use app\modules\users\{components\rbac\items\Permissions, components\rbac\RbacHelper, repositories\UserRepository};
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * @property int|null $group_id
 * @property int|null $report_id
 * @property int|null $struct_id
 * @property string|null $report_datetime
 * @property string|null $created_at
 * @property int|null $created_uid
 * @property string|null $hasConstant
 *
 * @property-read bool $onlyActive
 * @property-read array $groups
 * @property-read array $reports
 * @property-read array $constants
 * @property-read array $structures
 * @property-read array $usersAllow
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\search
 */
final class DataSearch extends Model
{
    use CleanDataProviderByRoleTrait;

    public $group_id;
    public $report_id;
    public $struct_id;
    public $report_datetime;
    public $created_at;
    public $created_uid;
    public $hasConstant;

    public readonly bool $onlyActive;
    public readonly array $groups;
    public readonly array $reports;
    public readonly array $constants;
    public readonly array $structures;
    public readonly array $usersAllow;

    public function __construct($config = [])
    {
        $this->onlyActive = RbacHelper::getOnlyActiveRecordsState([
            Permissions::DATA_VIEW_DELETE_MAIN,
            Permissions::DATA_VIEW_DELETE_GROUP,
            Permissions::DATA_VIEW_DELETE_ALL
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray('data.list.all');
        $this->reports = ReportRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->constants = ConstantRepository::getAllow(
            reports: array_keys($this->reports),
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->structures = StructureRepository::getAllow(
            reports: array_keys($this->reports),
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->usersAllow =  UserRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['group_id', 'report_id', 'struct_id', 'created_uid'], 'integer'],
            [['report_datetime', 'created_at'], 'string'],
            ['hasConstant', 'string', 'length' => [2,32]],

            ['group_id', 'in', 'range' => array_keys($this->groups)],
            ['report_id', 'in', 'range' => array_keys($this->reports)],
            ['struct_id', 'in', 'range' => array_keys($this->structures)],
            ['created_uid', 'in', 'range' => array_keys($this->usersAllow)],
            ['hasConstant', 'in', 'range' => array_keys($this->constants)]
        ];
    }

    public function attributeLabels(): array
    {
        return DataHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = DataRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive,
            asQuery: true
        )->with(['report', 'group', 'createdUser']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC]
            ],
            'pagination' => [
                'pageSize' => 50
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $this->cleanData($dataProvider);
        }

        $query->andFilterWhere(['=', 'group_id', $this->group_id])
            ->andFilterWhere(['=', 'report_id', $this->report_id])
            ->andFilterWhere(['=', 'struct_id', $this->struct_id])
            ->andFilterWhere(['=', 'created_uid', $this->created_uid]);

        if ($this->hasConstant) {
            $query->andFilterWhere(['REGEXP', 'content', '\b' . $this->hasConstant . '\b']);
        }

        if ($this->report_datetime) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->report_datetime));
            $query->andFilterWhere(['BETWEEN', 'report_datetime', $timePeriod[0], $timePeriod[1]]);
        }

        if ($this->created_at) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->created_at));
            $query->andFilterWhere(['BETWEEN', 'created_at', $timePeriod[0], $timePeriod[1]]);
        }

        return $this->cleanData($dataProvider);
    }

    private function cleanData($dataProvider): ActiveDataProvider
    {
        return $this->cleanDataProvider(
            dataProvider: $dataProvider,
            allDeleteRole: Permissions::DATA_VIEW_DELETE_ALL,
            groupDeleteRole: Permissions::DATA_VIEW_DELETE_GROUP,
            mainDeleteRole: Permissions::DATA_VIEW_DELETE_MAIN,
            allListRole: Permissions::DATA_LIST_ALL,
            groupListRole: Permissions::DATA_LIST_GROUP,
            mainListRole: Permissions::DATA_LIST_MAIN
        );
    }
}