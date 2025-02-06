<?php

namespace app\widgets\repeater;

use yii\web\AssetBundle;

use app\components\assets\CommonAsset;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\widgets\repeater
 */
class RepeaterAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $basePath = '@app';

    public $js = [
        'js/repeater.js',
    ];
    public $css = [
        'css/repeater.css',
    ];

    public $depends = [
        CommonAsset::class
    ];
}
