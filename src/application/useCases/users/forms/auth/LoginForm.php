<?php

namespace app\forms\auth;

use Yii;
use yii\base\Model;
use yii\bootstrap5\Html;

use app\entities\user\UserEntity;
use app\repositories\user\UserBaseRepository;
use app\helpers\{
    HtmlPurifier,
    AuthHelper
};

/**
 * @property string $email
 * @property string $password
 *
 * @property  UserEntity|null $_user
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\forms\auth
 */
final class LoginForm extends Model
{
    public $email;
    public $password;

    public ?UserEntity $user = null;

    public function rules(): array
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'min' => 4],
            ['email', 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)],
            [
                'email',
                'exist', 'targetClass' => UserEntity::class,
                'message' => Yii::t('models_error', 'Такого пользователя не существует')
            ],

            ['password', 'required'],
            ['password', 'string', 'min' => 4],

            ['password', 'validatePassword'],
            ['email', 'validateAccount']
        ];
    }

    public function attributeLabels(): array
    {
        return AuthHelper::labels();
    }

    public function validatePassword($attribute)
    {
        if ( !$this->hasErrors() ) {
            $user = $this->getUser();

            if (!Yii::$app->getSecurity()->validatePassword($this->password, $user->password)) {
                $this->addError($attribute, Yii::t('models_error', 'Вы указали неверный пароль'));
            }
        }
    }

    public function validateAccount($attribute)
    {
       if ( !$this->hasErrors() ) {
           $user = $this->getUser();

           if (
               $user->account_status == UserEntity::STATUS_WAITCONFIRM &&
               !Yii::$app->settings->get('auth', 'login_withoutVerification')
           ) {
               $this->addError(
                   $attribute,
                   Yii::t(
                       'models_error',
                       'Email Вашей учетной записи не подтвержден. Вы можете повторно запросить подтверждение, перейдя по {link}',
                       [
                           'link' => Html::a(Yii::t('entities', 'ссылке'), ['/verification'])
                       ]
                   )
               );
           }

           if ( $user->account_status == UserEntity::STATUS_BLOCKED ) {
               $this->addError($attribute, Yii::t('models_error', 'Ваша учетная запись заблокирована. Вход в систему невозможен'));
           }
       }
    }

    private function getUser(): ?UserEntity
    {
        if ( $this->user === null ) {
            $this->user = UserBaseRepository::getBy(['email' => $this->email]);
        }

        return $this->user;
    }
}