<?php

namespace app\search\group;

use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\repositories\group\{
    GroupRepository,
    GroupTypeRepository
};
use app\helpers\{
    CommonHelper,
    group\GroupHelper
};

/**
 * @property string $code
 * @property string $name
 * @property string $name_full
 * @property int $accept_send
 * @property int $type_id
 *
 * @property-read array $types
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\search\group
 */
final class GroupSearch extends Model
{
    public $code;
    public $name;
    public $name_full;
    public $accept_send;
    public $type_id;

    public readonly array $types;
    private readonly array $groups;

    public function __construct($config = [])
    {
        $this->types = GroupTypeRepository::getAll(
            asArray: true
        );

        $this->groups = GroupRepository::getAll(
            asArray: true
        );

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            ['code', 'string', 'length' => [2,6]],
            ['name', 'string', 'length' => [4,32]],
            ['name_full', 'string', 'length' => [4,255]],
            ['accept_send', 'integer'],
            ['type_id', 'integer'],
            ['type_id', 'in', 'range' => array_keys($this->types)]
        ];
    }

    public function attributeLabels(): array
    {
        return GroupHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = GroupRepository::getAllBy(
            condition: ['id' => array_keys($this->groups)],
            relations: ['type']
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

        if ( !($this->load($params) && $this->validate()) ) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'name_full', $this->name_full])
            ->andFilterWhere(['=', 'type_id', $this->type_id])
            ->andFilterWhere(['=', 'accept_send', CommonHelper::getFilterReplace($this->accept_send)]);

        return $dataProvider;
    }
}