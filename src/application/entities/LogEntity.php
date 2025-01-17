<?php

namespace app\entities;

use Yii;

use app\components\base\BaseAR;

/**
 * @property int $level
 * @property string $category
 * @property double $log_time
 * @property string $prefix
 * @property string $message
 *
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\entities
 */
class LogEntity extends BaseAR
{
    public function attributeLabels(): array
    {
        return [
            'level' => Yii::t('entities', 'Уровень'),
            'category' => Yii::t('entities', 'Категория'),
            'log_time' => Yii::t('entities', 'Время'),
            'prefix' => Yii::t('entities', 'Префикс'),
            'message' => Yii::t('entities', 'Сообщение'),
        ];
    }

    public static function tableName(): string
    {
        return '{{logs}}';
    }
}