<?php


use app\useCases\users\entities\user\UserEntity;
use yii\base\Event;
use yii\web\Request;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\events\objects
 */
final class AuthEvent extends Event
{
    public UserEntity $user;
    public ?Request $request;
}