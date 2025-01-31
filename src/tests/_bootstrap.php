<?php

defined('YII_DEBUG') or define('YII_DEBUG', env('YII_DEBUG', true));
defined('YII_ENV') or define('YII_ENV', env('YII_ENV', 'dev'));
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(__FILE__, 2) );

require_once YII_APP_BASE_PATH . '/vendor/autoload.php';
require_once YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
require_once YII_APP_BASE_PATH . '/application/config/bootstrap.php';
