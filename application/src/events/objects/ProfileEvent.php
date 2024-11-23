<?php

namespace app\events\objects;

use yii\base\Event;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\objects
 */
final class ProfileEvent extends Event
{
    public $userName;
    public $email;
    public $key;
}