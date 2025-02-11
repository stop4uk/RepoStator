<?php

use app\components\base\BaseAR;
use app\modules\users\entities\UserEntity;

$passwordForAccounts = '12345';

return [
    [
        'id' => 1,
        'email' => 'admin@test.loc',
        'password' => Yii::$app->getSecurity()->generatePasswordHash($passwordForAccounts),
        'lastname' => 'Администратор',
        'firstname' => 'тестовый',
        'phone' => null,
        'account_status' => UserEntity::STATUS_ACTIVE,
        'account_key' => Yii::$app->getSecurity()->generateRandomString(),
        'created_at' => time(),
        'record_status' => BaseAR::RSTATUS_ACTIVE,
    ],
    [
        'id' => 2,
        'email' => 'user1@test.loc',
        'password' => Yii::$app->getSecurity()->generatePasswordHash($passwordForAccounts),
        'lastname' => 'Первый',
        'firstname' => 'Пользователь',
        'phone' => null,
        'account_status' => UserEntity::STATUS_ACTIVE,
        'account_key' => Yii::$app->getSecurity()->generateRandomString(),
        'created_at' => time(),
        'record_status' => BaseAR::RSTATUS_ACTIVE,
    ],
    [
        'id' => 3,
        'email' => 'user2@test.loc',
        'password' => Yii::$app->getSecurity()->generatePasswordHash($passwordForAccounts),
        'lastname' => 'Второй',
        'firstname' => 'Пользователь',
        'middlename' => 'Отчество',
        'phone' => '9111111111',
        'account_status' => UserEntity::STATUS_ACTIVE,
        'account_key' => Yii::$app->getSecurity()->generateRandomString(),
        'created_at' => time(),
        'record_status' => BaseAR::RSTATUS_ACTIVE,
    ],
    [
        'id' => 4,
        'email' => 'user3@test.loc',
        'password' => Yii::$app->getSecurity()->generatePasswordHash($passwordForAccounts),
        'lastname' => 'Третий',
        'firstname' => 'Пользователь',
        'middlename' => 'Выключенный',
        'phone' => '9111111112',
        'account_status' => UserEntity::STATUS_BLOCKED,
        'account_key' => Yii::$app->getSecurity()->generateRandomString(),
        'created_at' => time(),
        'record_status' => BaseAR::RSTATUS_ACTIVE,
    ],
];