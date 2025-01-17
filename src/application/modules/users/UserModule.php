<?php

namespace app\modules;

use yii\base\{
    BootstrapInterface,
    Event,
    Module
};
use yii\helpers\ArrayHelper;

use app\modules\users\{
    events\dispatchers\AuthEventDispatcher,
    events\dispatchers\ProfileEventDispatcher,
    events\dispatchers\UserEventDispatcher,
    services\AuthService,
    services\ProfileService,
    services\UserService
};

final class UserModule extends Module implements BootstrapInterface
{
    public function init(): void
    {
        parent::init();
    }

    public function bootstrap($app): void
    {
        ArrayHelper::merge(
            $app->getUrlManager()->rules,
            require_once dirname(__FILE__, 1) . '/config/routes.php'
        );

        ArrayHelper::merge(
            $app->getLog()->targets,
            require_once dirname(__FILE__, 1) . '/config/' . match((bool)getenv('YII_DEBUG')) {
                true => 'logs_file.php',
                false => 'logs_db.php'
            }
        );

        Event::on(AuthService::class, AuthService::EVENT_AFTER_LOGIN, [AuthEventDispatcher::class, 'login']);
        Event::on(AuthService::class, AuthService::EVENT_BEFORE_LOGOUT, [AuthEventDispatcher::class, 'logout']);
        Event::on(AuthService::class, AuthService::EVENT_AFTER_REGISTER, [AuthEventDispatcher::class, 'register']);
        Event::on(AuthService::class, AuthService::EVENT_AFTER_RECOVERY_GET, [AuthEventDispatcher::class, 'recovery']);
        Event::on(AuthService::class, AuthService::EVENT_AFTER_VERIFICATION_GET, [AuthEventDispatcher::class, 'verification']);

        Event::on(UserService::class, UserService::EVENT_AFTER_ADD, [UserEventDispatcher::class, 'add']);
        Event::on(UserService::class, UserService::EVENT_AFTER_CHANGE, [UserEventDispatcher::class, 'change']);
        Event::on(UserService::class, UserService::EVENT_AFTER_DELETE, [UserEventDispatcher::class, 'delete']);

        Event::on(ProfileService::class, ProfileService::EVENT_AFTER_CHANGEEMAIL, [ProfileEventDispatcher::class, 'changeEmail']);
    }
}