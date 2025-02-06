<?php

namespace app\modules\users\helpers;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\helpers
 */
final class GroupHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'code' => Yii::t('entities', 'Код'),
            'name' => Yii::t('entities', 'Название'),
            'name_full' => Yii::t('entities', 'Полное название'),
            'description' => Yii::t('entities', 'Описание'),
            'accept_send' => Yii::t('entities', 'Отправка сведений'),
            'type_id' => Yii::t('entities', 'Тип'),
            'created_at' => Yii::t('entities', 'Создана'),
            'created_uid' => Yii::t('entities', 'Создал'),
            'updated_at' => Yii::t('entities', 'Обновлена'),
            'updated_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус группы')
        ];
    }
}