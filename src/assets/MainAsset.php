<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\assets
 */
final class MainAsset extends AssetBundle
{
    public $sourcePath = '@resources';
    public $css = [];
    public $js = [
        'assets/js/template/simplebar.js',
        'assets/js/web.js'
    ];
    public $depends = [
        CommonAsset::class
    ];
}
