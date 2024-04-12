<?php

namespace app\helpers\report;

use Yii;

use app\traits\GetLabelTrait;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\report
 */
final class ConstantRuleHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'record' => Yii::t('entities', 'Идентификатор'),
            'name' => Yii::t('entities', 'Название'),
            'description' => Yii::t('entities', 'Описание'),
            'rule' => Yii::t('entities', 'Математика'),
            'report_id' => Yii::t('entities', 'Только для отчета'),
            'groups_only' => Yii::t('entities', 'Учитывать группы'),
            'create_at' => Yii::t('entities', 'Создано'),
            'create_uid' => Yii::t('entities', 'Создал'),
            'update_at' => Yii::t('entities', 'Обновлено'),
            'update_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус правила'),

            'hasConstant' => Yii::t('models', 'Константа в правиле'),
            'limitGroup' => Yii::t('models', 'Ограничена группой'),
            'limitReport' => Yii::t('models', 'Ограничена отчетом')
        ];
    }
}