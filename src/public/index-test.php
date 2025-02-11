<?php

defined('YII_DEBUG') or define('YII_DEBUG', getenv('YII_DEBUG', true));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV', 'test'));

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use yii\web\Application;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

$config = ArrayHelper::merge(
    require __DIR__ . '/../application/config/web.php',
    require __DIR__ . '/../application/config/codeception.php',
    require __DIR__ . '/../application/config/test_components.php',
);

try {
    (new Application($config))->run();
} catch (InvalidConfigException $e) {}
