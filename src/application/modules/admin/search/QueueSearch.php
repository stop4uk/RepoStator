<?php

namespace app\modules\admin\search;

use yii\data\ActiveDataProvider;

use app\entities\QueueEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\admin\search
 */
final class QueueSearch extends QueueEntity
{
    public function rules(): array
    {
        return [
            ['id', 'integer'],
            [['job', 'channel', 'pushed_at', 'done_at'], 'string']
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = QueueEntity::find();
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
            return $dataProvider;
        }

        $query->andFilterWhere(['=', 'id', $this->id])
            ->andFilterWhere(['like', 'job', $this->job])
            ->andFilterWhere(['like', 'channel', $this->channel]);

        if ($this->pushed_at) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->pushed_at));
            $query->andFilterWhere(['BETWEEN', 'pushed_at', $timePeriod[0], $timePeriod[1]]);
        }

        if ($this->done_at) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->done_at));
            $query->andFilterWhere(['BETWEEN', 'done_at', $timePeriod[0], $timePeriod[1]]);
        }

        return $dataProvider;
    }
}