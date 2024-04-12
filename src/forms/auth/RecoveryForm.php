<?php

namespace app\forms\auth;

use Yii;
use yii\base\Model;
use yii\helpers\HtmlPurifier;

use app\base\BaseAR;
use app\entities\user\UserEntity;
use app\repositories\user\UserRepository;
use app\helpers\{
    AuthHelper,
    user\UserHelper
};

/**
 * @property string|null $email
 * @property string|null $password;
 * @property string|null $verifyPassword;
 * @property string|null $authKey;
 *
 * @private UserEntity|null $_user;
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\forms\auth
 */
final class RecoveryForm extends Model
{
    const SCENARIO_PROCESS = 'process';

    public $email;
    public $password;
    public $verifyPassword;
    public $authKey;

    private $_user;

    public function __construct(?string $key = null, $config = [])
    {
        parent::__construct($config);
        $this->authKey = $key;
    }

    public function rules(): array
    {
        $filterStatuses = [UserEntity::STATUS_ACTIVE];
        if ( !Yii::$app->settings->get('auth', 'login_withoutVerification') ) {
            $filterStatuses[] = UserEntity::STATUS_WAITCONFIRM;
        }

        return [
            ['email', 'required', 'on' => self::SCENARIO_DEFAULT],
            ['email', 'string', 'min' => 4, 'on' => self::SCENARIO_DEFAULT],
            ['email', 'trim', 'on' => self::SCENARIO_DEFAULT],
            ['email', 'email', 'on' => self::SCENARIO_DEFAULT],
            ['email', 'filter', 'filter' => fn($value) => HtmlPurifier::process($value), 'on' => self::SCENARIO_DEFAULT],
            [
                'email', 'exist', 'targetClass' => UserEntity::class, 'targetAttribute' => 'email',
                'filter' => ['record_status' => BaseAR::RSTATUS_ACTIVE, 'account_status' => $filterStatuses],
                'message' => Yii::t('entities', 'Для данной учетной записи восстановление не предусмотрено'),
                'on' => self::SCENARIO_DEFAULT
            ],

            ['authKey', 'checkUser', 'on' => self::SCENARIO_PROCESS],
            [
                ['password', 'verifyPassword'],
                'required', 'on' => self::SCENARIO_PROCESS,
                'message' => Yii::t('models_error', 'Все поля обязательны для заполнения'),
                'on' => self::SCENARIO_PROCESS
            ],
            [
                ['password', 'verifyPassword'],
                'string', 'min' => 5,
                'message' => Yii::t('models_error', 'Минимальная длина пароля 5 символов'),
                'on' => self::SCENARIO_PROCESS
            ],
            [
                'password',
                'compare', 'compareAttribute' => 'verifyPassword', 'on' => self::SCENARIO_PROCESS,
                'message' => Yii::t('models_error', 'Пароль и подтверждение пароля не совпадают'),
                'on' => self::SCENARIO_PROCESS
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return AuthHelper::labels();
    }

    public function checkUser()
    {
        if ( $this->hasErrors() ) {
            return false;
        }

        if ( !$user = $this->getUser() ) {
            $this->addError('password', Yii::t('models_error', 'Пользователь с таким ключем восстановления отсутствует'));
        }
    }

    public function afterValidate()
    {
        if ( $this->scenario == self::SCENARIO_PROCESS && !$this->hasErrors() ) {
            $this->password = UserHelper::generatePassword($this->password);
        }

        parent::afterValidate();
    }

    private function getUser(): ?UserEntity
    {
        if ( $this->_user === null ) {
            $this->_user = UserRepository::getBy(['account_key' => $this->authKey]);
        }

        return $this->_user;
    }
}