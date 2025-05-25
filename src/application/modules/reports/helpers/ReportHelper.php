<?php

namespace app\modules\reports\helpers;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\helpers
 */
final class ReportHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'name' => Yii::t('entities', 'Название'),
            'description' => Yii::t('entities', 'Описание'),
            'groups_only' => Yii::t('entities', 'Предназначен'),
            'groups_required' => Yii::t('entities', 'Обязателен для групп'),
            'left_period' => Yii::t('entities', 'Перерыв в минутах'),
            'block_minutes' => Yii::t('entities', 'Закрытие передачи (в мин)'),
            'null_day' => Yii::t('entities', 'Расчет периода с начала суток'),
            'allow_dynamicForm' => Yii::t('entities', 'Разрешить расчет статистики без шаблонов'),
            'created_at' => Yii::t('entities', 'Создан'),
            'created_uid' => Yii::t('entities', 'Создал'),
            'updated_at' => Yii::t('entities', 'Обновлен'),
            'updated_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус отчета'),

            'hasGroupOnly' => Yii::t('models', 'Ограничен группой'),
            'hasGroupRequired' => Yii::t('models', 'Ожидает группу'),
            'allow_dynamicFormSearch' => Yii::t('models', 'Разрешена статистика без шаблона')
        ];
    }
}