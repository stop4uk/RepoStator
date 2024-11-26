<?php

namespace app\helpers;

use traits\GetLabelTrait;
use Yii;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers
 */
final class AuthHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'email' => Yii::t('entities', 'Email'),
            'password' => Yii::t('entities', 'Пароль'),
            'verifyPassword' => Yii::t('entities', 'Подтверждение пароля'),
            'lastname' => Yii::t('entities', 'Фамилия'),
            'firstname' => Yii::t('entities', 'Имя'),
            'middlename' => Yii::t('entities', 'Отчество'),
            'phone' => Yii::t('entities', 'Телефон'),
        ];
    }
}