<?php

use yii\web\Application;
use yii\helpers\ArrayHelper;

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = ArrayHelper::merge(
    require __DIR__ . '/../config/common/main.php',
    require __DIR__ . '/../config/common/main-local.php',
    require __DIR__ . '/../config/common/databases-local.php',
    require __DIR__ . '/../config/common/logs-local.php',
    require __DIR__ . '/../config/web/main.php',
    require __DIR__ . '/../config/web/main-local.php',
);

(new Application($config))->run();
