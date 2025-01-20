<?php

namespace app\modules\reports\helpers;

use Yii;

use app\traits\GetLabelTrait;
use app\modules\reports\entities\ReportEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers\report
 */
final class DataHelper
{
    use GetLabelTrait;

    public static function labels(): array
    {
        return [
            'report_id' => Yii::t('entities', 'Отчет'),
            'group_id' => Yii::t('entities', 'Группа'),
            'struct_id' => Yii::t('entities', 'Структура'),
            'report_datetime' => Yii::t('entities', 'Отчетный период'),
            'content' => Yii::t('entities', 'Содержание'),
            'created_at' => Yii::t('entities', 'Передан'),
            'created_uid' => Yii::t('entities', 'Передал'),
            'updated_at' => Yii::t('entities', 'Обновлен'),
            'updated_uid' => Yii::t('entities', 'Обновил'),
            'record_status' => Yii::t('entities', 'Статус переданных сведений'),
            'hasConstant' => Yii::t('models', 'Содержит константу')
        ];
    }

    public static function getTimePeriods(ReportEntity $model, string|int $toDate, bool $onlyLast = false): ?object
    {
        if ( !$model->left_period ) {
            return null;
        }

        $periods = [];
        $timeToSeconds = ($model->left_period * 60);
        $timeBlockToSeconds = 0;
        $roundTime = strtotime(date(( ($model->null_day) ? 'Y-m-d' : 'Y-m-d H:00:00'), $model->created_at));

        if ( $model->block_minutes ) {
            $timeBlockToSeconds = ($model->block_minutes * 60);
        }

        while ($toDate >= $roundTime ) {
            $endRoundTime = (($roundTime+$timeToSeconds)-60);
            $endRoundTimeWithBlock = ($endRoundTime - $timeBlockToSeconds);

            $periods[] = [
                'start' => $roundTime,
                'end' => $endRoundTimeWithBlock
            ];

            $roundTime = ($endRoundTime+60);
        }

        $list = array_slice($periods, -100);
        return (object)(( $onlyLast ) ? end($list) : $list);
    }
}