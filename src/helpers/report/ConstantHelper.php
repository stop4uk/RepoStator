<?php

namespace app\helpers\report;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\report
 */
final class ConstantHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'record' => Yii::t('entities', 'Идентификатор'),
            'name' => Yii::t('entities', 'Название'),
            'name_full' => Yii::t('entities', 'Полное название'),
            'description' => Yii::t('entities', 'Описание'),
            'union_rules' => Yii::t('entities', 'Правила объединения'),
            'reports_only' => Yii::t('entities', 'Только для отчетов'),
            'create_at' => Yii::t('entities', 'Созданы'),
            'create_uid' => Yii::t('entities', 'Создал'),
            'update_at' => Yii::t('entities', 'Обновлены'),
            'update_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус прав доступа'),

            'limitReport' => Yii::t('models', 'Ограничена отчетом')
        ];
    }
}