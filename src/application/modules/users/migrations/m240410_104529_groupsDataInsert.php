<?php

use yii\db\Migration;

class m240410_104529_groupsDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%groups}}',
                           ["id", "code", "name", "name_full", "description", "accept_send", "type_id", "created_at", "created_uid", "updated_at", "updated_uid", "record_status"],
                            [
    [
        'id' => 1,
        'code' => '001',
        'name' => 'Главная группа',
        'name_full' => null,
        'description' => null,
        'accept_send' => 0,
        'type_id' => null,
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
        //$this->truncateTable('{{%groups}} CASCADE');
    }
}
