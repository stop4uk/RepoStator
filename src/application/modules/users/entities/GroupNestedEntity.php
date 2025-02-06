<?php

namespace app\modules\users\entities;

use yii\db\ActiveQuery;
use creocoder\nestedsets\NestedSetsBehavior;

use app\components\base\BaseAR;

/**
 * @property int group_id
 * @property int $lft
 * @property int $rgt
 * @property int|null $depth
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\entities
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
        return '{{%groups_nested}}';
    }
}