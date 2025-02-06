<?php

define('YII_DEBUG', getenv('YII_DEBUG', true));
define('YII_ENV', getenv('YII_ENV', 'test'));

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use yii\web\Application;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

$config = ArrayHelper::merge(
    require __DIR__ . '/../application/config/test.php',
    require __DIR__ . '/../application/config/bootstrap.php',
);

try {
    (new Application($config))->run();
} catch (InvalidConfigException $e) {}
