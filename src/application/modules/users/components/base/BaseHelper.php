<?php

namespace stop4uk\users\base;

use Yii;
use yii\base\Exception;
use yii\helpers\{
    ArrayHelper,
    FileHelper
};
use yii\bootstrap5\Html;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers
 */
final class BaseHelper
{
    const VAR_YES = 1;
    const VAR_NO = 0;

    const VALUES = [
        self::VAR_NO,
        self::VAR_YES
    ];

    public static function saveAttempt(BaseAR $entity, string $category, bool $validate = true): BaseAR|bool
    {
        try {
            $entity->save($validate);
            return $entity;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), $category);
        }

        return false;
    }

    public static function explodeField(string $string): array
    {
        return explode(', ', $string);
    }

    public static function implodeField(array $array): string
    {
        return implode(', ', $array);
    }

    public static function getDefaultDropdown(): array
    {
        return [
            self::VAR_YES => Yii::t('views', 'Да'),
            self::VAR_NO => Yii::t('views', 'Нет'),
        ];
    }

    public static function getDefaultDropdownRecord(): array
    {
        return [
            self::VAR_YES => Yii::t('views', 'Активна'),
            self::VAR_NO => Yii::t('views', 'Скрыта#Удалена'),
        ];
    }

    public static function getDefaultDropdownColor(): array
    {
        return [
            self::VAR_YES => 'success',
            self::VAR_NO => 'primary',
        ];
    }

    public static function getDefaultDropdownColorRecord(): array
    {
        return [
            self::VAR_YES => 'success',
            self::VAR_NO => 'danger',
        ];
    }

    public static function getYesOrNo(int $code): ?string
    {
        return ArrayHelper::getValue(self::getDefaultDropdown(), $code);
    }

    public static function getYesOrNoRecord(int $code): ?string
    {
        return ArrayHelper::getValue(self::getDefaultDropdownRecord(), $code);
    }

    public static function getYesOrNoRecordColor(int $code): ?string
    {
        return ArrayHelper::getValue(self::getDefaultDropdownColorRecord(), $code);
    }

    public static function getYesOrNoColor(int $code): ?string
    {
        return Html::tag('span', self::getYesOrNo($code), [
            'class' => 'badge bg-' . ArrayHelper::getValue(self::getDefaultDropdownColor(), $code)
        ]);
    }

    public static function getYesOrNoColorRecord(int $code): ?string
    {
        return Html::tag('span', self::getYesOrNoRecord($code), [
            'class' => 'badge bg-' . ArrayHelper::getValue(self::getDefaultDropdownColorRecord(), $code)
        ]);
    }

    public static function getFilterReplace(int|string|null $filterCode = null): ?int
    {
        if ( $filterCode ) {
            return ( $filterCode == 99 ) ? 0 : $filterCode;
        }

        return null;
    }

    public static function getFilterReplaceData(array $filterData): array
    {
        if ( isset($filterData[99]) ) {
            $filterData[99] = $filterData[0];
            unset($filterData[0]);
        }

        return $filterData;
    }
}