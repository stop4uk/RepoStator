<?php

namespace app\modules\users\events;

use yii\base\{
    BootstrapInterface,
    Event
};

use app\modules\users\events\dispatchers\{
    UserEventDispatcher,
    AuthEventDispatcher,
    ProfileEventDispatcher
};
use app\modules\users\services\{
    AuthService,
    ProfileService,
    UserService
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\handlers
 */
final class EventHandler implements BootstrapInterface
{
    public function bootstrap($app): void
    {
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
