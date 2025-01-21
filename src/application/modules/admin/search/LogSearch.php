<?php

namespace app\modules\admin\search;

use yii\data\ActiveDataProvider;

use app\entities\LogEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\search
 */
final class LogSearch extends LogEntity
{
    public function rules(): array
    {
        return [
            ['level', 'integer'],
            ['category', 'string', 'length' => [2, 10]],
            ['prefix', 'string', 'length' => [2, 24]],
            ['message', 'string', 'length' => [2, 50]],
            ['log_time', 'string']
        ];
    }

    public function search($params): ActiveDataProvider
    {
        $query = parent::find();
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
            return $dataProvider;
        }

        $query->andFilterWhere(['=', 'level', $this->level])
            ->andFilterWhere(['like', 'category', $this->category])
            ->andFilterWhere(['like', 'prefix', $this->prefix])
            ->andFilterWhere(['like', 'message', $this->message]);

        if ( $this->log_time ) {
            $timePeriod = array_map(fn($value) => strtotime($value), explode(' - ', $this->log_time));
            $query->andFilterWhere(['BETWEEN', 'log_time', $timePeriod[0], $timePeriod[1]]);
        }

        return $dataProvider;
    }
}