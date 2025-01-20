<?php
namespace app\modules\reports\widgets\repeater;

use yii\web\AssetBundle;

use app\components\assets\CommonAsset;

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
