<?php

namespace app\entities\group;

use app\components\base\BaseAR;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\db\ActiveQuery;

/**
 * @property int group_id
 * @property int $lft
 * @property int $rgt
 * @property int|null $depth
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\group
 */
final class GroupNestedEntity extends BaseAR
{
    public function behaviors(): array
    {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::class,
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['group_id'], 'required'],
            [['group_id', 'lft', 'rgt', 'depth'], 'integer'],
        ];
    }

    public function getGroup(): ActiveQuery
    {
        return $this->hasOne(GroupEntity::class, ['id' => 'group_id']);
    }

    public static function tableName(): string
    {
        return '{{groups_nested}}';
    }
}