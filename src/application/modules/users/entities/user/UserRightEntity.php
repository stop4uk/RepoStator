<?php

namespace entities\user;

use app\components\base\BaseAR;
use Yii;
use yii\behaviors\{BlameableBehavior, TimestampBehavior};

/**
 * @property string $item_name
 * @property int $user_id
 * @property int $created_at
 * @property int $created_uid
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\user
 */
final class UserRightEntity extends BaseAR
{
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'value' => time(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['created_uid'],
                ],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            ['item_name', 'string'],
            [['user_id', 'created_at', 'created_uid'], 'integer']
        ];
    }

    public static function tableName(): string
    {
        return Yii::$app->getAuthManager()->assignmentTable;
    }
}