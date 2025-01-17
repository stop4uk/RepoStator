<?php

namespace app\useCases\users\helpers\user;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\user
 */
final class UserGroupHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'group_id' => Yii::t('entities', 'Группа'),
            'user_id' => Yii::t('entities', 'Сотрудник'),
            'created_at' => Yii::t('entities', 'Добавлен'),
            'created_uid' => Yii::t('entities', 'Добавил'),
            'updated_at' => Yii::t('entities', 'Исключен'),
            'updated_uid' => Yii::t('entities', 'Исключил'),
            'record_status' => Yii::t('entities', 'Текущий статус'),
        ];
    }
}