<?php
namespace app\useCases\reports\widgets\repeater;

use app\assets\CommonAsset;
use yii\web\AssetBundle;

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
