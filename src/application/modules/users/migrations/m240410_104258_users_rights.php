<?php

use yii\db\Migration;

class m240410_104258_users_rights extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%users_rights}}',
            [
                'item_name'=> $this->string(64)->notNull(),
                'user_id'=> $this->integer(11)->notNull(),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('IDX_user_id','{{%users_rights}}',['user_id'],false);
        $this->addPrimaryKey('pk_on_users_rights','{{%users_rights}}',['item_name','user_id']);

    }

    public function safeDown()
    {
    $this->dropPrimaryKey('pk_on_users_rights','{{%users_rights}}');
        $this->dropIndex('IDX_user_id', '{{%users_rights}}');
        $this->dropTable('{{%users_rights}}');
    }
}
