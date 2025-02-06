<?php

namespace app\modules\users\helpers;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\helpers
 */
final class GroupTypeHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'id' => '#',
            'name' => Yii::t('entities', 'Название'),
            'description' => Yii::t('entities', 'Описание'),
            'created_at' => Yii::t('entities', 'Создан'),
            'created_uid' => Yii::t('entities', 'Создал'),
            'updated_at' => Yii::t('entities', 'Обновлен'),
            'updated_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус типа')
        ];
    }
}