<?php

namespace app\entities\user;

use Yii;
use yii\behaviors\{
    BlameableBehavior,
    TimestampBehavior
};

use app\base\BaseAR;
use app\helpers\{
    HtmlPurifier,
    user\UserSessionHelper
};

/**
 * @property int $user_id
 * @property string $ip
 * @property string $client
 * @property string|null $additional
 * @property int $created_at
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities\user
 */
final class UserSessionEntity extends BaseAR
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
                    self::EVENT_BEFORE_INSERT => ['user_id'],
                ],
            ],
        ];
    }

    public function rules(): array
    {
        return [
            ['ip', 'default', 'value' => Yii::$app->getRequest()->getUserIP()],
            ['client', 'default', 'value' => Yii::$app->getRequest()->getUserAgent()],
            ['additional', 'default', 'value' => 'TestAdditionalData'],
            [['ip', 'client'], 'filter', 'filter' => fn ($value) => HtmlPurifier::process($value)],

            [['ip', 'client'], 'required'],
        ];
    }

    public function attributeLabels(): array
    {
        return UserSessionHelper::labels();
    }

    public static function tableName(): string
    {
        return '{{users_sessions}}';
    }
}