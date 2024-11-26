<?php

namespace app\useCases\system\entities;

use Yii;

use app\components\base\BaseAR;

/**
 * @property string $channel
 * @property string job
 * @property int $pushed_at
 * @property int $ttr
 * @property int $delay
 * @property int priority
 * @property int reserved_at
 * @property int attempt
 * @property int $done_at
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities
 */
class QueueEntity extends BaseAR
{
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('entities', '#'),
            'channel' => Yii::t('entities', 'Канал'),
            'job' => Yii::t('entities', 'Задача'),
            'pushed_at' => Yii::t('entities', 'Поставлена'),
            'delay' => Yii::t('entities', 'Задержка'),
            'priority' => Yii::t('entities', 'Приоритет'),
            'done_at' => Yii::t('entities', 'Завершена'),
            'attempt' => Yii::t('entities', 'Попытка'),
        ];
    }

    public static function tableName(): string
    {
        return '{{queue}}';
    }
}