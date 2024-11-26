<?php


use app\traits\GetLabelTrait;
use entities\ReportFormTemplateEntity;
use yii\helpers\ArrayHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\report
 */
final class TemplateHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'name' => Yii::t('entities', 'Название'),
            'report_id' => Yii::t('entities', 'Отчет'),
            'use_grouptype' => Yii::t('entities', 'Заголовки по типам групп'),
            'form_datetime' => Yii::t('entities', 'Период расчета'),
            'form_type' => Yii::t('entities', 'Тип формирования'),
            'form_result' => Yii::t('entities', 'Тип вывода'),
            'form_usejobs' => Yii::t('entities', 'Использовать очередь'),
            'table_type' => Yii::t('entites', 'Тип колонок'),
            'table_rows' => Yii::t('entities', 'Список строк'),
            'table_columns' => Yii::t('entities', 'Список колонок'),
            'table_template' => Yii::t('entities', 'Файл-шаблон'),
            'limit_maxfiles' => Yii::t('entities', 'Макс. файлов для отчета'),
            'limit_maxsavetime' => Yii::t('entities', 'Макс. время хранения (сек)'),
            'created_at' => Yii::t('entities', 'Создан'),
            'created_uid' => Yii::t('entities', 'Создал'),
            'updated_at' => Yii::t('entities', 'Обновлен'),
            'updated_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус правила вывода'),
            'constant' => Yii::t('entities', 'Константа'),
            'constantRule' => Yii::t('entities', 'Правило'),
            'group' => Yii::t('entities', 'Группа'),
            'groupType' => Yii::t('entities', 'Тип группы'),
            'use_appg' => Yii::t('entities', 'С АППГ'),
            'uploadedFile' => Yii::t('models', 'Файл-шаблон'),

            'hasConstant' => Yii::t('models', 'Содержит константу'),
            'hasConstantRule' => Yii::t('models', 'Содержит правило'),
            'hasGroup' => Yii::t('models', 'Содержит группу'),
            'hasGroupType' => Yii::t('models', 'Содержит тип группы'),
        ];
    }

    public static function getTypes(): array
    {
        $items = [
            ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC => Yii::t('entities', 'Динамичeский набор правил'),
            ReportFormTemplateEntity::REPORT_TYPE_TEMPLATE => Yii::t('entities', 'Формирование из файла-шаблона'),
        ];

        if ( !Yii::$app->settings->get('report', 'make_dynamic') ) {
            unset($items[ReportFormTemplateEntity::REPORT_TYPE_DYNAMIC]);
        }

        return $items;
    }

    public static function getTableTypes(): array
    {
        return [
            ReportFormTemplateEntity::REPORT_TABLE_TYPE_CONST => Yii::t('models', 'Константы и правила'),
            ReportFormTemplateEntity::REPORT_TABLE_TYPE_GROUP => Yii::t('models', 'Группы'),
        ];
    }

    public static function getDatetimeTypes(): array
    {
        return [
            ReportFormTemplateEntity::REPORT_DATETIME_WEEK => Yii::t('models', 'Неделя'),
            ReportFormTemplateEntity::REPORT_DATETIME_MONTH => Yii::t('models', 'Месяц'),
            ReportFormTemplateEntity::REPORT_DATETIME_PERIOD => Yii::t('models', 'Произвольный период')
        ];
    }

    public static function getDatetimeFieldLabels(): array
    {
        return [
            ReportFormTemplateEntity::REPORT_DATETIME_WEEK => 'use_week',
            ReportFormTemplateEntity::REPORT_DATETIME_MONTH => 'use_month',
            ReportFormTemplateEntity::REPORT_DATETIME_PERIOD => 'use_period'
        ];
    }

    public static function getType(int $code): ?string
    {
        return ArrayHelper::getValue(self::getTypes(), $code);
    }

    public static function getResult(int $code): ?string
    {
        return ArrayHelper::getValue(self::getResultTypes(), $code);
    }

    public static function getDatetimeType(int $code): ?string
    {
        return ArrayHelper::getValue(self::getDatetimeTypes(), $code);
    }
}