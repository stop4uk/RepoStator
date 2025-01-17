<?php

namespace app\modules\users\events\dispatchers;

use yii\base\Event;
use yii\web\Request;

use app\modules\users\entities\UserEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\objects
 */
final class AuthEvent extends Event
{
    public UserEntity $user;
    public ?Request $request;
}