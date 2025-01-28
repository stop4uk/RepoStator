<?php

namespace app\modules\users\forms;

use Yii;
use yii\base\Model;

use app\helpers\HtmlPurifier;
use app\modules\users\{
    entities\UserEntity,
    helpers\UserHelper,
    helpers\AuthHelper
};

/**
 * @property string $email
 * @property string $password
 * @property string $lastname
 * @property string $firstname
 * @property string|null $middlename
 * @property int $phone
 * @property int $account_status
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\forms
 */
final class RegisterForm extends Model
{
    public $email;
    public $password;
    public $lastname;
    public $firstname;
    public $middlename;
    public $phone;
    public $account_status;

    public function rules(): array
    {
        return [
            [['email', 'password', 'lastname', 'firstname'], 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'length' => [4, 58]],
            ['password', 'string', 'min' => 5],
            ['lastname', 'string', 'length' => [2, 48]],
            [['firstname', 'middlename'], 'string', 'length' => [2, 24]],
            ['phone', 'string', 'length' => [10, 10]],
            ['phone', 'integer'],
            [['email', 'lastname', 'firstname', 'middlename', 'phone'], 'filter', 'filter' => fn ($value) => HtmlPurifier::process($value)],
            [
                'email', 'unique', 'targetClass' => UserEntity::class, 'targetAttribute' => 'email',
                'message' => Yii::t('models_error', 'Данный Email адрес уже зарегистрирован в системе')
            ],
            ['account_status', 'default', 'value' => Yii::$app->settings->get('auth', 'login_withoutVerification')
                ? UserEntity::STATUS_ACTIVE
                : UserEntity::STATUS_WAITCONFIRM
            ]
        ];
    }

    public function attributeLabels(): array
    {
        return AuthHelper::labels();
    }

    public function afterValidate(): void
    {
        if ( !$this->hasErrors() ) {
            $this->password = UserHelper::generatePassword($this->password);
        }

        parent::afterValidate();
    }
}