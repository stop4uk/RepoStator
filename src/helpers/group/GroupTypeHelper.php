<?php

namespace app\helpers\group;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\group
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
            'create_at' => Yii::t('entities', 'Создан'),
            'create_uid' => Yii::t('entities', 'Создал'),
            'update_at' => Yii::t('entities', 'Обновлен'),
            'update_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус типа')
        ];
    }
}