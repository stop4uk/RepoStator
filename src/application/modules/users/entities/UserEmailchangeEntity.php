<?php

namespace app\modules\users\entities;

use yii\behaviors\{
    BlameableBehavior,
    TimestampBehavior
};
use yii2tech\ar\softdelete\SoftDeleteBehavior;

use app\components\base\BaseAR;

/**
 * @property int $user_id
 * @property string $email
 * @property string $key
 * @property int $created_at
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\entities
 */
final class UserEmailchangeEntity extends BaseAR
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
                    self::EVENT_BEFORE_INSERT => ['user_id'],
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

    public static function tableName(): string
    {
        return '{{%users_emailchanges}}';
    }
}