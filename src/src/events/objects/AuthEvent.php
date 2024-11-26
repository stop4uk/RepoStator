<?php

namespace app\events\objects;

use yii\base\Event;
use yii\web\Request;

use app\entities\user\UserEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\objects
 */
final class AuthEvent extends Event
{
    public UserEntity $user;
    public ?Request $request;
}