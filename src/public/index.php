<?php

define('YII_DEBUG', getenv('YII_DEBUG'));
define('YII_ENV', getenv('YII_ENV'));

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

use yii\web\Application;

$config = require __DIR__ . '/../application/config/web.php';

(new Application($config))->run();
