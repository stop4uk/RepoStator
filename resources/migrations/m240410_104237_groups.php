<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104237_groups extends Migration
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
            '{{%groups}}',
            [
                'id'=> $this->primaryKey(11),
                'code'=> $this->string(6)->null()->defaultValue(null),
                'name'=> $this->string(64)->notNull()->defaultValue(''),
                'name_full'=> $this->string(255)->null()->defaultValue(null),
                'description'=> $this->text()->null()->defaultValue(null),
                'accept_send'=> $this->tinyInteger(1)->null()->defaultValue(null),
                'type_id'=> $this->integer(11)->null()->defaultValue(null),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
                'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('UQ_name','{{%groups}}',['name'],true);
        $this->createIndex('UQ_code','{{%groups}}',['code'],true);
        $this->createIndex('IDX_accept_send','{{%groups}}',['accept_send'],false);
        $this->createIndex('IDX_type_id','{{%groups}}',['type_id'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('UQ_name', '{{%groups}}');
        $this->dropIndex('UQ_code', '{{%groups}}');
        $this->dropIndex('IDX_accept_send', '{{%groups}}');
        $this->dropIndex('IDX_type_id', '{{%groups}}');
        $this->dropTable('{{%groups}}');
    }
}
