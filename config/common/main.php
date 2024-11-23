<?php

use yii\i18n\PhpMessageSource;

use app\bootstrap\CoreBootstrap;
use app\events\handlers\CoreEventHandler;
use app\components\Settings;

return [
    'name' => "REPOStator. Reports & Statistics",
    'sourceLanguage' => 'ru',
    'basePath' => dirname(__DIR__) . '/../',
    'runtimePath' => dirname(__DIR__) . '/../storage',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@root' => dirname(__DIR__) . '/../',
        '@app' => dirname(__DIR__) . '/../src',
        '@runtime' => '@root/storage',
        '@resources' => '@root/resources',
        '@downloads' => '@runtime/files',
        '@uploads' => '@root/data',
        '@templates' => '@uploads/formTemplate',
    ],
    'bootstrap' => [
        'log',
        CoreBootstrap::class,
        CoreEventHandler::class,
    ],
    'components' => [
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => PhpMessageSource::class,
                    'basePath' => '@resources/messages',
                    'forceTranslation' => true,
                ],
            ],
        ],
        'formatter' => [
            'thousandSeparator' => '.',
            'decimalSeparator' => '.',
            'nullDisplay' => '',
            'numberFormatterOptions' => [
                NumberFormatter::MIN_FRACTION_DIGITS => 0,
            ]
        ],
        'settings' => [
            'class' => Settings::class,
            'preLoad' => [
                'system'
            ]
        ],
    ],
];