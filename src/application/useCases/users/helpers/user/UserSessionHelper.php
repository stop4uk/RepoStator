<?php

namespace app\useCases\users\helpers\user;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\user
 */
final class UserSessionHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'user_id' => Yii::t('entities', 'Сотрудник'),
            'ip' => Yii::t('entities', 'IP адрес'),
            'client' => Yii::t('entities', 'Клиент'),
            'additional' => Yii::t('entities', 'Дополнительное инфо'),
            'created_at' => Yii::t('entities', 'Дата и время')
        ];
    }
}