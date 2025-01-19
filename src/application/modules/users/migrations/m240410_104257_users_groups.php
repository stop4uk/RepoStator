<?php

use yii\db\Migration;

class m240410_104257_users_groups extends Migration
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
            '{{%users_groups}}',
            [
                'id'=> $this->primaryKey(11)->unsigned(),
                'user_id'=> $this->integer(11)->notNull(),
                'group_id'=> $this->integer(11)->notNull(),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
                'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('IDX_user_id','{{%users_groups}}',['user_id'],false);
        $this->createIndex('IDX_group_id','{{%users_groups}}',['group_id'],false);
        $this->createIndex('IDX_record_starus','{{%users_groups}}',['record_status'],false);
        $this->createIndex('IDX_created_uid','{{%users_groups}}',['created_uid'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('IDX_user_id', '{{%users_groups}}');
        $this->dropIndex('IDX_group_id', '{{%users_groups}}');
        $this->dropIndex('IDX_record_starus', '{{%users_groups}}');
        $this->dropIndex('IDX_created_uid', '{{%users_groups}}');
        $this->dropTable('{{%users_groups}}');
    }
}
