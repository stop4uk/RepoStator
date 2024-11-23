<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104259_users_sessions extends Migration
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
            '{{%users_sessions}}',
            [
                'id'=> $this->primaryKey(11)->unsigned(),
                'user_id'=> $this->integer(11)->notNull(),
                'ip'=> $this->string(45)->notNull()->defaultValue(''),
                'client'=> $this->string(255)->notNull()->defaultValue(''),
                'additional'=> $this->text()->null()->defaultValue(null),
                'created_at'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('IDX_user_id','{{%users_sessions}}',['user_id'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('IDX_user_id', '{{%users_sessions}}');
        $this->dropTable('{{%users_sessions}}');
    }
}
