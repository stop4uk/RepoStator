<?php

use app\modules\users\{
    events\dispatchers\AuthEventDispatcher,
    events\dispatchers\ProfileEventDispatcher,
    events\dispatchers\UserEventDispatcher,
    services\AuthService,
    services\ProfileService,
    services\UserService
};

return [
    [
        'class' => AuthService::class,
        'event' => AuthService::EVENT_AFTER_LOGIN,
        'callable' => [AuthEventDispatcher::class, 'login']
    ],
    [
        'class' => AuthService::class,
        'event' => AuthService::EVENT_BEFORE_LOGOUT,
        'callable' => [AuthEventDispatcher::class, 'logout']
    ],
    [
        'class' => AuthService::class,
        'event' => AuthService::EVENT_AFTER_REGISTER,
        'callable' => [AuthEventDispatcher::class, 'register']
    ],
    [
        'class' => AuthService::class,
        'event' => AuthService::EVENT_AFTER_RECOVERY_GET,
        'callable' => [AuthEventDispatcher::class, 'recovery']
    ],
    [
        'class' => AuthService::class,
        'event' => AuthService::EVENT_AFTER_VERIFICATION_GET,
        'callable' => [AuthEventDispatcher::class, 'verification']
    ],

    [
        'class' => UserService::class,
        'event' => UserService::EVENT_AFTER_ADD,
        'callable' => [UserEventDispatcher::class, 'add']
    ],
    [
        'class' => UserService::class,
        'event' => UserService::EVENT_AFTER_CHANGE,
        'callable' => [UserEventDispatcher::class, 'change']
    ],
    [
        'class' => UserService::class,
        'event' => UserService::EVENT_AFTER_DELETE,
        'callable' => [UserEventDispatcher::class, 'delete']
    ],

    [
        'class' => ProfileService::class,
        'event' => ProfileService::EVENT_AFTER_CHANGEEMAIL,
        'callable' => [ProfileEventDispatcher::class, 'changeEmail']
    ],
];
