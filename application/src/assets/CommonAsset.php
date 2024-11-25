<?php

namespace app\assets;

use yii\web\{
    AssetBundle,
    YiiAsset
};

use yii\bootstrap5\{
    BootstrapPluginAsset,
    BootstrapIconAsset
};

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
        YiiAsset::class,
        BootstrapIconAsset::class,
        BootstrapPluginAsset::class,
    ];
}
