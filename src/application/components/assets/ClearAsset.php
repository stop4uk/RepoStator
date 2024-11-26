<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\assets
 */
final class ClearAsset extends AssetBundle
{
    public $sourcePath = '@resources';
    public $css = [];
    public $js = [];
    public $depends = [
        CommonAsset::class
    ];
}
