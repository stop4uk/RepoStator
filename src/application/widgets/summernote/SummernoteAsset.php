<?php

namespace app\widgets\summernote;

use Yii;
use yii\web\AssetBundle;

use app\components\assets\CommonAsset;

final class SummernoteAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';
    public $basePath = '@app';

    public $js = [
        'js/summernote-bs5.min.js',
        'js/codemirror.min.js',
        'js/xml.js',
    ];
    public $css = [
        'css/summernote-bs5.min.css',
        'css/codemirror.min.css',
        'css/monokai.css',
    ];

    public $depends = [
        CommonAsset::class
    ];

    public function init(): void
    {
        $this->js[] = 'js/summernote-' . Yii::$app->language . '.min.js';
        parent::init();
    }
}