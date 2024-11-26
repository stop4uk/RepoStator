<?php

use yii\db\Schema;
use yii\db\Migration;

class m240703_125858_settingsDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%settings}}', ["category", "key", "value", "description", "required", "sort"], [
            [
                'category' => 'auth',
                'key' => 'login_recovery',
                'value' => '1',
                'description' => 'Разрешить самостоятельное восстановление пароля',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'users_notification_add',
                'value' => '1',
                'description' => 'Отправлять уведомление о добавлении пользователя',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'users_notification_delete',
                'value' => '1',
                'description' => 'Отправлять уведомление об удалении пользователя',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'users_notification_change',
                'value' => '1',
                'description' => 'Отправлять уведомление об изменении пользователя',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'auth',
                'key' => 'profile_enableChangeEmail',
                'value' => '1',
                'description' => 'Разрешить самостоятельную смену Email',
                'required' => 0,
                'sort' => 5,
            ],
        ]);
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%settings}} CASCADE');
    }
}
