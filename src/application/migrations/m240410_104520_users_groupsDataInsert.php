<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104520_users_groupsDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%users_groups}}',
                           ["id", "user_id", "group_id", "created_at", "created_uid", "updated_at", "updated_uid", "record_status"],
                            [
    [
        'id' => 1,
        'user_id' => 1,
        'group_id' => 1,
        'created_at' => 1708349986,
        'created_uid' => 1,
        'updated_at' => null,
        'updated_uid' => null,
        'record_status' => 1,
    ],
]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%users_groups}} CASCADE');
    }
}
