<?php

namespace app\useCases\users\entities\user;

use yii\db\ActiveQuery;
use yii\behaviors\{
    BlameableBehavior,
    TimestampBehavior
};
use yii2tech\ar\softdelete\SoftDeleteBehavior;

use app\components\base\BaseAR;
use app\useCases\users\helpers\user\UserGroupHelper;

/**
 * @property int $user_id
 * @property int $group_id
 * @property int $created_at
 * @property int $created_uid
 * @property int|null $updated_at
 * @property int|null $updated_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\user
 */
final class UserGroupEntity extends BaseAR
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => time(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                    self::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_uid'],
                    self::EVENT_BEFORE_UPDATE => ['updated_uid'],
                ],
            ],
            [
                'class' => SoftDeleteBehavior::class,
                'softDeleteAttributeValues' => [
                    'record_status' => self::RSTATUS_DELETED
                ],
            ]
        ];
    }

    public function attributeLabels(): array
    {
        return UserGroupHelper::labels();
    }

    public function scenarios(): array
    {
        return [
            self::SCENARIO_INSERT => ['user_id', 'group_id'],
        ];
    }

    public function getUser(): ActiveQuery
    {
        return $this->hasOne(UserEntity::class, ['id' => 'user_id']);
    }

    public static function tableName(): string
    {
        return '{{users_groups}}';
    }
}