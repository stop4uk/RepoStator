<?php

namespace app\helpers;

use Closure;
use HTMLPurifier as GeneralPurifier;
use HTMLPurifier_Config;

use Yii;
use yii\helpers\HtmlPurifier as BasePurifier;

final class HtmlPurifier extends BasePurifier
{
    public static function process($content, $config = null): string
    {
        $configInstance = HTMLPurifier_Config::create($config instanceof Closure ? null : $config);
        $configInstance->autoFinalize = false;
        $purifier = GeneralPurifier::instance($configInstance);
        $purifier->config->set('Cache.SerializerPath', Yii::$app->getRuntimePath());
        $purifier->config->set('Cache.SerializerPermissions', 0775);
        $purifier->config->set('HTML.Allowed', 'p,a[href|rel|target|title],img[src],span[style],strong,em,ul,ol,li,table[id|class],tr[id|class],td[id|class],thead,tbody,tfoot[id|class]');

        static::configure($configInstance);
        if ($config instanceof Closure) {
            call_user_func($config, $configInstance);
        }

        return $purifier->purify($content);
    }
}