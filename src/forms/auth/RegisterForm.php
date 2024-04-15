<?php

namespace app\forms\auth;

use Yii;
use yii\base\Model;
use yii\helpers\HtmlPurifier;

use app\entities\user\UserEntity;
use app\helpers\{
    AuthHelper,
    user\UserHelper
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
 * @package app\forms\auth
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

    public function afterValidate()
    {
        if ( !$this->hasErrors() ) {
            $this->password = UserHelper::generatePassword($this->password);
        }

        parent::afterValidate();
    }
}