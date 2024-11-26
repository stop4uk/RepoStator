<?php

namespace traits;

use yii\helpers\ArrayHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\traits
 */
trait GetLabelTrait
{
    public static function getLabel(string $attribute): ?string
    {
        return ArrayHelper::getValue(static::labels(), $attribute);
    }
}