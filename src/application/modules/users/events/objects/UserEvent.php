<?php

namespace app\modules\users\events\dispatchers;

use yii\base\Event;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\objects
 */
final class UserEvent extends Event
{
    public $user;
    public $userEntity;
}