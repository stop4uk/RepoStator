<?php

namespace app\components\attachedFiles\widgets\fileupload\assets;

use yii\web\{
    AssetBundle,
    JqueryAsset
};
use yii\bootstrap5\BootstrapAsset;

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
