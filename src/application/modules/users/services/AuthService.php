<?php

namespace app\modules\users\services;

use Yii;
use yii\base\{
    Component,
    Exception
};
use yii\web\Request;
use yii\helpers\Json;

use app\components\{
    base\BaseServiceInterface,
    base\BaseARInterface,
    base\BaseAR,
};
use app\helpers\CommonHelper;
use app\modules\users\{
    components\Identity,
    events\objects\AuthEvent,
    entities\UserEmailchangeEntity,
    entities\UserEntity,
    repositories\UserRepository,
    forms\LoginForm,
    forms\RecoveryForm,
    forms\RegisterForm,
    forms\authVerificationForm,
};


/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\services
 */
final class AuthService extends Component implements BaseServiceInterface
{
    const EVENT_AFTER_LOGIN = 'auth.afterLogin';
    const EVENT_BEFORE_LOGOUT = 'auth.beforeLogout';
    const EVENT_AFTER_REGISTER = 'auth.afterRegister';
    const EVENT_AFTER_RECOVERY_GET = 'auth.afterRecoveryGet';
    const EVENT_AFTER_VERIFICATION_GET = 'auth.afterVerificationGet';

    public function login(
        LoginForm $model,
        Request $request
    ): bool {
        if (!$model->user) {
            throw new Exception(Yii::t('exceptions', 'Авторизация невозможна ввиду отсутствия данных пользователя'), 400);
        }

        if (!$this->loginProcess($model->user)) {
            Yii::error($this->loginProcess($model->user), 'Auth.Signin');
            throw new Exception(Yii::t('exceptions', 'В процессе авторизации возникли ошибки'), 400);
        }

        $this->trigger(self::EVENT_AFTER_LOGIN, new AuthEvent([
            'user' => $model->user,
            'request' => $request
        ]));

        return true;
    }

    public function logout(): void
    {
        $this->trigger(self::EVENT_BEFORE_LOGOUT, new AuthEvent([
            'user' => UserRepository::get(Yii::$app->getUser()->id)
        ]));

        if (!Yii::$app->getUser()->logout()) {
            throw new Exception(Yii::t('exceptions', 'При прекращении работы сессии произошла ошибка'), 500);
        }
    }

    public function register(
        RegisterForm $model,
        Request $request
    ): BaseARInterface {
        $entity = new UserEntity(['scenario' => BaseAR::SCENARIO_INSERT]);

        $entity->recordAction($model);
        $transaction = Yii::$app->db->beginTransaction();

        if (
            $newUser = CommonHelper::saveAttempt(
                entity: $entity,
                category: 'Users.Signup'
            )
        ) {
            $transaction->commit();

            $this->trigger(self::EVENT_AFTER_REGISTER, new AuthEvent([
                'user' => $newUser,
                'request' => $request
            ]));

            if (
                Yii::$app->settings->get('auth', 'login_withoutVerification')
                && !$this->loginProcess($newUser)
            ) {
                throw new Exception(Yii::t('exceptions', 'Регистрация успешно завершена, однако, в процессе авторизации возникли ошибки'), 500);
            }

            return $newUser;
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', 'В процессе регистрации пользователя возникли ошибки'), 500);
    }

    public function recovery(
        RecoveryForm $model,
        Request $request
    ): bool {
        if (
            $user = UserEntity::find()
                ->where([
                    'email' => $model->email
                ])
                ->limit(1)
                ->one()
        ) {
            $this->trigger(self::EVENT_AFTER_RECOVERY_GET, new AuthEvent([
                'user' => $user,
                'request' => $request
            ]));

            return true;
        }

        Yii::error('Recovery error: '.Json::encode($request), 'Auth.Recovery');
        return false;
    }

    public function recoveryProcess(RecoveryForm $model): bool
    {
        $user = UserEntity::find()
            ->where(['account_key' => $model->authKey])
            ->limit(1)
            ->one();

        if ($user) {
            $user->password = $model->password;
            $user->account_key = Yii::$app->getSecurity()->generateRandomString();

            if (CommonHelper::saveAttempt($user, 'Users.Auth')) {
                return true;
            }
        }

        Yii::error('Recovery process error: '.Json::encode($model), 'Auth.Recovery');
        throw new Exception(Yii::t('exceptions', 'При восстановлении доступа и смене пароля произошла ошибка. Пожалуйста, обратитесь к администратору'));
    }

    public function verification(VerificationForm $model, Request $request): bool
    {
        $user = UserEntity::find()
            ->where([
                'email' => $model->email
            ])
            ->limit(1)
            ->one();

        if ($user) {
            $this->trigger(self::EVENT_AFTER_VERIFICATION_GET, new AuthEvent([
                'user' => $user,
                'request' => $request
            ]));

            return true;
        }

        Yii::error('Verification error: '.Json::encode($request), 'Auth.Verification');
        return false;
    }

    public function verificationProcess(string $authKey): bool
    {
        $user = UserEntity::find()
            ->where([
                'account_key' => $authKey,
                'account_status' => UserEntity::STATUS_WAITCONFIRM
            ])
            ->limit(1)
            ->one();

        if ($user) {
            $user->account_status = UserEntity::STATUS_ACTIVE;
            $user->account_key = Yii::$app->getSecurity()->generateRandomString(32);
            if (CommonHelper::saveAttempt($user, 'Users.Auth')) {
                return true;
            }
        }

        Yii::error('Verification process error: '.Json::encode($authKey), 'Auth.Verification');
        throw new Exception(Yii::t('exceptions', 'При верификации Email адреса произошла ошибка. Пожалуйста, обратитесь к администратору'));
    }

    public function changeEmail(string $authKey): bool
    {
        $request = UserEmailchangeEntity::find()
            ->where([
                'key' => $authKey,
                'record_status' => BaseAR::RSTATUS_ACTIVE
            ])
            ->limit(1)
            ->one();

        if ($request) {
            $request->record_status = BaseAR::RSTATUS_DELETED;
            $transaction = Yii::$app->db->beginTransaction();

            if (CommonHelper::saveAttempt($request, 'Users.InitialData'))  {
                $user = UserRepository::get($request->user_id);
                $user->scenario = $user::SCENARIO_CHANGE_EMAIL;
                $user->email = $request->email;
                if (CommonHelper::saveAttempt($user, 'Users.Profile')) {
                    $transaction->commit();
                    return true;
                }
            }

            $transaction->rollBack();
        }

        throw new Exception(Yii::t('exceptions', 'При обновлении Email возникли ошибки. Пожалуйста, обратитесь к администратору'));
    }

    private function loginProcess(UserEntity $user): bool
    {
        return Yii::$app->getUser()->login(
            identity: new Identity($user),
            duration: Yii::$app->settings->get('auth', 'login_duration')
        );
    }
}
