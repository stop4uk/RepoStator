<?php

namespace app\forms\auth;

use app\components\base\BaseAR;
use app\entities\user\UserEntity;
use app\helpers\{AuthHelper, HtmlPurifier};
use Yii;
use yii\base\Model;

/**
 * @property string|null $email
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\forms\auth
 */
final class VerificationForm extends Model
{
    public $email;

    public function rules(): array
    {
        return [
            ['email', 'required'],
            ['email', 'string', 'min' => 4],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
            [
                'email', 'exist', 'targetClass' => UserEntity::class, 'targetAttribute' => 'email',
                'filter' => ['record_status' => BaseAR::RSTATUS_ACTIVE, 'account_status' => UserEntity::STATUS_WAITCONFIRM],
                'message' => Yii::t('entities', 'Для данной учетной записи верификация не предусмотрена')
            ]
        ];
    }

    public function attributeLabels(): array
    {
        return AuthHelper::labels();
    }
}