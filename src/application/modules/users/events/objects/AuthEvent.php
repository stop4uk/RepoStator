<?php

namespace stop4uk\users\events\dispatchers;

use yii\base\Event;
use yii\web\Request;

use stop4uk\users\entities\UserEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\objects
 */
final class AuthEvent extends Event
{
    public UserEntity $user;
    public ?Request $request;
}