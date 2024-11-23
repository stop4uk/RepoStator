<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\assets
 */
final class CommonAsset extends AssetBundle
{
    public $sourcePath = '@resources';
    public $css = [
        'assets/css/template.css',
        'assets/css/core.css',
    ];
    public $js = [
        'assets/js/core.js'
    ];
    public $depends = [
        \yii\web\YiiAsset::class,
        \yii\bootstrap5\BootstrapIconAsset::class,
        \yii\bootstrap5\BootstrapPluginAsset::class,
    ];
}
