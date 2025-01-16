<?php

namespace app\useCases\reports\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\Html;

use app\traits\GetLabelTrait;
use app\useCases\reports\entities\ReportFormJobEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\report
 */
final class JobHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'job_id' => Yii::t('entities', 'Задача'),
            'job_status' => Yii::t('entities', 'Статус'),
            'file' => Yii::t('entities', 'Файл'),
            'report_id' => Yii::t('entities', 'Отчет'),
            'template_id' => Yii::t('entities', 'Шаблон'),
            'form_period' => Yii::t('entities', 'Период расчета'),
            'created_at' => Yii::t('entities', 'Запрошен'),
            'created_uid' => Yii::t('entities', 'Запросил'),
            'created_gid' => Yii::t('entities', 'Группа'),
            'updated_at' => Yii::t('entities', 'Обновлен'),
            'record_status' => Yii::t('entities', 'Статус задачи')
        ];
    }

    public static function statuses(): array
    {
        return [
            ReportFormJobEntity::STATUS_WAIT => Yii::t('entities', 'Ожидает'),
            ReportFormJobEntity::STATUS_COMPLETE => Yii::t('entities', 'Сформирован'),
            ReportFormJobEntity::STATUS_ERROR => Yii::t('entities', 'Ошибка'),
        ];
    }

    public static function statusesInColor(): array
    {
        return [
            ReportFormJobEntity::STATUS_WAIT => 'info',
            ReportFormJobEntity::STATUS_COMPLETE => 'success',
            ReportFormJobEntity::STATUS_ERROR => 'danger'
        ];
    }

    public static function statusName(int $statusCode): ?string
    {
        return ArrayHelper::getValue(static::statuses(), $statusCode);
    }

    public static function statusNameInColor(int $statusCode): ?string
    {
        return Html::tag('span', ArrayHelper::getValue(self::statuses(), $statusCode), [
            'class' => 'badge bg-' . ArrayHelper::getValue(self::statusesInColor(), $statusCode)
        ]);
    }
}