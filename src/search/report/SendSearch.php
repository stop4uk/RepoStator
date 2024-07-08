<?php

namespace app\search\report;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\{
    HtmlPurifier,
    ArrayHelper
};

use app\entities\report\ReportDataEntity;
use app\repositories\{
    group\GroupRepository,
    report\ReportRepository
};
use app\helpers\{
    CommonHelper,
    RbacHelper,
    report\ReportHelper,
    report\DataHelper
};

/**
 * @property string|null $name
 *
 * @property-read array $groups
 * @private array $groupsCanSent
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\search\report
 */
final class SendSearch extends Model
{
    public $name;

    public readonly array $groups;
    private readonly array $groupsCanSent;

    public function __construct($config = [])
    {
        $this->groups = RbacHelper::getAllowGroupsArray('data.send.all');
        $this->groupsCanSent = GroupRepository::getAllBy(
            condition: ['accept_send' => 1, 'id' => array_keys($this->groups)],
            asArray: true
        );

        return parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['name', 'string', 'length' => [4,64]],
            ['name', 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
        ];
    }

    public function attributeLabels(): array
    {
        return ReportHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $groups = $this->groupsCanSent;
        $subQueryForMaxRecordsIds = ReportDataEntity::find()
            ->select('MAX(id) as id')
            ->andFilterWhere(['in', 'group_id', array_keys($groups)])
            ->orderBy(['id DESC', 'report_datetime DESC'])
            ->groupBy(['report_id', 'group_id'])
            ->limit(1)
            ->asArray();

        $query = ReportRepository::getAllow(
            groups: $this->groups,
            asQuery: true,
        )->with([
            'data' => function($query) use ($groups, $subQueryForMaxRecordsIds) {
                $query->andFilterWhere(['in', 'group_id', array_keys($groups)])
                    ->andFilterWhere(['in', 'id', ArrayHelper::map($subQueryForMaxRecordsIds, 'id', 'id')])
                    ->orderBy('report_datetime DESC');
            }
        ]);

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
            return $this->filterResult($dataProvider);
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $this->filterResult($dataProvider);
    }

    private function filterResult(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $models = $dataProvider->getModels();
        if ( !$models ) {
            return $dataProvider;
        }

        foreach ($models as $index => $model) {
            $nowTime = time();
            $sentReports = ArrayHelper::map($model->data, 'group_id', 'report_datetime');
            $model->timePeriod = DataHelper::getTimePeriods($model, $nowTime, true);

            if ( !$sentReports ) {
                foreach ($this->groupsCanSent as $groupId => $groupName) {
                    if (
                        !$model->groups_only ||
                        in_array($groupId, CommonHelper::explodeField($model->groups_only))
                    ) {
                        if (
                            !$model->timePeriod ||
                            ( $nowTime >= $model->timePeriod->start && $nowTime <= $model->timePeriod->end )
                        ) {
                            $model->canAddedFor[] = [
                                'groupId' => $groupId,
                                'groupName' => $groupName,
                                'reportId' => $model->id,
                            ];
                        }
                    }
                }

                if ( !$model->canAddedFor ) {
                    unset($models[$index]);
                }

                continue;
            }

            foreach ($this->groupsCanSent as $groupId => $groupName) {
                if (
                    !$model->groups_only ||
                    in_array($groupId, CommonHelper::explodeField($model->groups_only))
                ) {
                    if (
                        !$model->timePeriod ||
                        ($nowTime >= $model->timePeriod->start && $nowTime <= $model->timePeriod->end)
                    ) {
                        if ( !in_array($groupId, array_keys($sentReports)) ) {
                            $model->canAddedFor[] = [
                                'groupId' => $groupId,
                                'groupName' => $groupName,
                                'reportId' => $model->id,
                            ];

                            continue;
                        }

                        if (
                            !$model->timePeriod ||
                            !($sentReports[$groupId] >= $model->timePeriod->start && $sentReports[$groupId] <= $model->timePeriod->end)
                        ) {
                            $model->canAddedFor[] = [
                                'groupId' => $groupId,
                                'groupName' => $groupName,
                                'reportId' => $model->id,
                            ];
                        }
                    }
                }
            }

            if ( !$model->canAddedFor ) {
                unset($models[$index]);
            }
        }

        $dataProvider->setModels($models);
        return $dataProvider;
    }
}
