<?php

use yii\db\Migration;

class m240410_104256_users_emailchanges extends Migration
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
            '{{%users_emailchanges}}',
            [
                'id'=> $this->primaryKey(11)->unsigned(),
                'user_id'=> $this->integer(11)->notNull(),
                'email'=> $this->string(58)->notNull()->defaultValue(''),
                'key'=> $this->string(32)->notNull()->defaultValue(''),
                'created_at'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('IDX_user_id','{{%users_emailchanges}}',['user_id'],false);
        $this->createIndex('IDX_email','{{%users_emailchanges}}',['email'],false);
        $this->createIndex('IDX_key','{{%users_emailchanges}}',['key'],false);
        $this->createIndex('IDX_record_status','{{%users_emailchanges}}',['record_status'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('IDX_user_id', '{{%users_emailchanges}}');
        $this->dropIndex('IDX_email', '{{%users_emailchanges}}');
        $this->dropIndex('IDX_key', '{{%users_emailchanges}}');
        $this->dropIndex('IDX_record_status', '{{%users_emailchanges}}');
        $this->dropTable('{{%users_emailchanges}}');
    }
}
