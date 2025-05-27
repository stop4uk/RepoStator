<?php

namespace app\helpers;

use ReflectionClass;

use Yii;
use yii\web\Request;
use yii\base\{
    Model,
    Exception
};
use yii\helpers\{
    ArrayHelper,
    FileHelper
};
use yii\bootstrap5\Html;

use app\components\base\{
    BaseModel,
    BaseAR
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\helpers
 */
final class CommonHelper
{
    const VAR_YES = 1;
    const VAR_NO = 0;

    const VALUES = [
        self::VAR_NO,
        self::VAR_YES
    ];

    public static function formFilter(
        BaseModel|Model $searchModel,
        Request &$request,
        string $sessionName
    ): array
    {
        $session = Yii::$app->getSession();
        $modelName = (new ReflectionClass($searchModel))->getShortName();
        $post = $request->post();

        if ($request->isGet) {
            $session->remove($sessionName);
            return [];
        }

        if ($request->isPost) {
            $filters = $post[$modelName] ?? [];
            $haveFilters = $session->get($sessionName)[$modelName] ?? [];
            if ($request->getQueryParam('page') !== null) {
                if ($filters) {
                    $request->setQueryParams(['page' => 1]);
                    $session->set($sessionName, $post);
                    return $post;
                }

                return $haveFilters
                    ? $session->get($sessionName)
                    : $post;
            }

            $session->set($sessionName, $post);
        }

        return $post;
    }

    public static function saveAttempt(
        BaseAR $entity,
        string $category,
        bool $validate = true
    ): BaseAR|bool {
        try {
            if ($entity->validate() && $entity->save($validate)) {
                return $entity;
            }

            Yii::error($entity->getErrors(), $category);
        } catch (Exception $e) {
            Yii::error($e->getMessage(), $category);
        }

        return false;
    }

    public static function getDataShowAttribute($model): int
    {
        $notCleanAttributes = [];
        $outCleanAttributes = array_filter($model->attributes);

        if ($outCleanAttributes) {
            foreach ($outCleanAttributes as $attribute => $value) {
                if (!is_bool($model->{$attribute}) && !is_array($model->{$attribute})) {
                    $notCleanAttributes[$attribute] = $value;
                }
            }
        }

        return (int)$notCleanAttributes;
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
        if ($filterCode) {
            return ($filterCode == 99 ) ? 0 : $filterCode;
        }

        return null;
    }

    public static function getFilterReplaceData(array $filterData): array
    {
        if (isset($filterData[99])) {
            $filterData[99] = $filterData[0];
            unset($filterData[0]);
        }

        return $filterData;
    }

    public static function getRangesForDate(): array
    {
        return [
            Yii::t('views', 'Вчера') => [
                "moment().startOf('day').subtract(1,'days')",
                "moment().endOf('day').subtract(1,'days')"
            ],
            Yii::t('views', 'Сегодня') => [
                "moment().startOf('day')",
                "moment().endOf('day')"
            ],
            Yii::t('views', 'Неделя') => [
                "moment().startOf('week')",
                "moment().endOf('day')"
            ],
            Yii::t('views', 'Месяц') => [
                "moment().startOf('month')",
                "moment().endOf('month')"
            ],
            Yii::t('views', 'Текущий год') => [
                "moment().startOf('year')",
                "moment().endOf('year')"
            ],
        ];
    }
}