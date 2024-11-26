<?php

namespace search\group;

use helpers\group\GroupTypeHelper;
use repositories\group\GroupTypeRepository;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * @property string $name
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\search\group
 */
final class GroupTypeSearch extends Model
{
    public $name;

    public function rules(): array
    {
        return [
            ['name', 'string', 'length' => [2,32]],
        ];
    }

    public function attributeLabels(): array
    {
        return GroupTypeHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = GroupTypeRepository::getAll();

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
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}