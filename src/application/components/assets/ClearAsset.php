<?php

namespace app\components\assets;

use yii\web\AssetBundle;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\componetns\assets
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
