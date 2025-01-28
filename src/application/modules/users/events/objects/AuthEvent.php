<?php

namespace app\modules\users\events\objects;

use yii\base\Event;
use yii\web\Request;

use app\modules\users\entities\UserEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\events\objects
 */
final class AuthEvent extends Event
{
    public UserEntity $user;
    public ?Request $request;
}