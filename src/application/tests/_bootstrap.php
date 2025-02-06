<?php

define('YII_DEBUG', getenv('YII_DEBUG', true));
define('YII_ENV', getenv('YII_ENV', 'dev'));
defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(__FILE__, 2));

require_once YII_APP_BASE_PATH . '/../vendor/autoload.php';
require_once YII_APP_BASE_PATH . '/../vendor/yiisoft/yii2/Yii.php';
require_once YII_APP_BASE_PATH . '/config/bootstrap.php';
