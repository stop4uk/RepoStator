<?php

use yii\db\Migration;

class m240410_104521_users_rightsDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%users_rights}}',
                           ["item_name", "user_id", "created_at", "created_uid"],
                            [
    [
        'item_name' => 'admin',
        'user_id' => 1,
        'created_at' => 1708349986,
        'created_uid' => 1,
    ],
]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%users_rights}} CASCADE');
    }
}
