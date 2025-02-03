<?php
namespace app\modules\reports\widgets\duplicating;

use yii\web\AssetBundle;

use app\components\assets\CommonAsset;

final class DuplicatingAsset extends AssetBundle
{
    public $sourcePath = __DIR__;
    public $basePath = '@app';

    public $js = [
        'js/duplicate.js',
    ];

    public $depends = [
        CommonAsset::class
    ];
}
