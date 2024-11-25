<?php

namespace app\helpers\report;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\report
 */
final class StructureHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'name' => Yii::t('entities', 'Название'),
            'report_id' => Yii::t('entities', 'Отчет'),
            'groups_only' => Yii::t('entities', 'Для групп'),
            'content' => Yii::t('entities', 'Содержание'),
            'use_union_rules' => Yii::t('entities', 'Группировка полей'),
            'created_at' => Yii::t('entities', 'Создана'),
            'created_uid' => Yii::t('entities', 'Создал'),
            'updated_at' => Yii::t('entities', 'Обновлена'),
            'updated_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус структуры'),
            'contentGroups' => Yii::t('entities', 'Раздел'),
            'contentConstants' => Yii::t('entities', 'Содержимое раздела'),

            'hasGroup' => Yii::t('models', 'Ограничен группой')
        ];
    }
}