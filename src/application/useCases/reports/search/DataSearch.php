<?php

namespace app\search\report;

use app\helpers\{RbacHelper, report\DataHelper};
use app\repositories\{report\ConstantBaseRepository,
    report\DataBaseRepository,
    report\ReportBaseRepository,
    report\StructureBaseRepository,
    user\UserBaseRepository};
use traits\CleanDataProviderByRoleTrait;
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
 * @package app\search\report
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
            'data.view.delete.main',
            'data.view.delete.group',
            'data.view.delete.all'
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray('data.list.all');
        $this->reports = ReportBaseRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->constants = ConstantBaseRepository::getAllow(
            reports: array_keys($this->reports),
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->structures = StructureBaseRepository::getAllow(
            reports: array_keys($this->reports),
            groups: $this->groups,
            active: $this->onlyActive
        );
        $this->usersAllow =  UserBaseRepository::getAllow(
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
        $query = DataBaseRepository::getAllow(
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
                'pageSize' => 15
            ],
        ]);

        if ( !($this->load($params) && $this->validate()) ) {
            return $this->cleanData($dataProvider);
        }

        $query->andFilterWhere(['=', 'group_id', $this->group_id])
            ->andFilterWhere(['=', 'report_id', $this->report_id])
            ->andFilterWhere(['=', 'struct_id', $this->struct_id])
            ->andFilterWhere(['=', 'created_uid', $this->created_uid]);

        if ( $this->hasConstant ) {
            $query->andFilterWhere(['REGEXP', 'content', '\b' . $this->hasConstant . '\b']);
        }

        if ( $this->report_datetime ) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->report_datetime));
            $query->andFilterWhere(['BETWEEN', 'report_datetime', $timePeriod[0], $timePeriod[1]]);
        }

        if ( $this->created_at ) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->created_at));
            $query->andFilterWhere(['BETWEEN', 'created_at', $timePeriod[0], $timePeriod[1]]);
        }

        return $this->cleanData($dataProvider);
    }

    private function cleanData($dataProvider): ActiveDataProvider
    {
        return $this->cleanDataProvider(
            dataProvider: $dataProvider,
            allDeleteRole: 'data.view.delete.all',
            groupDeleteRole: 'data.view.delete.group',
            mainDeleteRole: 'data.view.delete.main',
            allListRole: 'data.list.all',
            groupListRole: 'data.list.group',
            mainListRole: 'data.list.main'
        );
    }
}