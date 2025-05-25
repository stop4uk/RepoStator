<?php

namespace app\modules\reports\search;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;

use app\helpers\HtmlPurifier;
use app\modules\reports\{
    entities\ReportDataEntity,
    helpers\DataHelper,
    helpers\ReportHelper,
    repositories\ReportRepository,
};
use app\modules\users\{
    components\rbac\RbacHelper,
    repositories\GroupRepository
};

/**
 * @property string|null $name
 *
 * @property-read array $groups
 * @private array $groupsCanSent
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\search
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
            ['name', 'string', 'length' => [2,64]],
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
            asQuery: true,
        );

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC]
            ],
            'pagination' => [
                'pageSize' => 15
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $this->filterResult($dataProvider);
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $this->filterResult($dataProvider);
    }

    private function filterResult(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $models = $dataProvider->getModels();
        if (!$models) {
            return $dataProvider;
        }

        foreach ($models as $index => $model) {
            $nowTime = time();

            $model->timePeriod = DataHelper::getTimePeriods($model, $nowTime, true);
            $model->canAddedFor = [];

            if ($this->groupsCanSent) {
                $canAddedNow = true;
                $sentData = [];

                if ($model->timePeriod) {
                    $canAddedNow = ($nowTime >= $model->timePeriod->start && $nowTime <= $model->timePeriod->end);
                    $dataQuery = ReportDataEntity::find()
                        ->select('group_id')
                        ->where(['report_id' => $model->id])
                        ->andWhere(['between', 'report_datetime', $model->timePeriod->start, $model->timePeriod->end])
                        ->groupBy(['group_id'])
                        ->asArray();

                    $sentData = ArrayHelper::map($dataQuery->all(), 'group_id', 'group_id');
                }

                foreach ($this->groupsCanSent as $groupId => $groupName) {
                    if (
                        (
                            !$model->groups_only
                            || (
                                (!is_array($model->groups_only) && $model->groups_only == $groupId)
                                || (is_array($model->groups_only) && in_array($groupId, $model->groups_only))
                            )
                        )
                        && $canAddedNow
                    ) {
                        if (
                            ($sentData && !in_array($groupId, $sentData))
                            || !$sentData
                            || !$model->timePeriod
                        ) {
                            $model->canAddedFor[] = [
                                'groupId' => $groupId,
                                'groupName' => $groupName,
                                'reportId' => $model->id,
                            ];

                            continue;
                        }
                    }
                }
            }

            if (!$model->canAddedFor) {
                unset($models[$index]);
            }
        }

        $dataProvider->setModels($models);
        return $dataProvider;
    }
}
