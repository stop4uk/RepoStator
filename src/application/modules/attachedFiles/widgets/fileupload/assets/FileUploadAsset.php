<?php

namespace widgets\fileupload\assets;

use yii\bootstrap5\BootstrapAsset;
use yii\web\{AssetBundle, JqueryAsset};

class FileUploadAsset extends AssetBundle
{
    public $sourcePath = '@bower/blueimp-file-upload';
    public $css = [
        'css/jquery.fileupload.css'
    ];
    public $js = [
        'js/vendor/jquery.ui.widget.js',
        'js/jquery.iframe-transport.js',
        'js/jquery.fileupload.js'
    ];
    public $depends = [
        JqueryAsset::class,
        BootstrapAsset::class,
    ];
    public $publishOptions = [
        'except' => [
            'server/*',
            'test'
        ],
    ];
}
