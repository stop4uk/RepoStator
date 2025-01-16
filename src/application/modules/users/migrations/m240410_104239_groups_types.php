<?php

use yii\db\Migration;

class m240410_104239_groups_types extends Migration
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
            '{{%groups_types}}',
            [
                'id'=> $this->primaryKey(11),
                'name'=> $this->string(64)->notNull()->defaultValue(''),
                'description'=> $this->text()->null()->defaultValue(null),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
                'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('UQ_name','{{%groups_types}}',['name'],false);
        $this->createIndex('IDX_record_status','{{%groups_types}}',['record_status'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('UQ_name', '{{%groups_types}}');
        $this->dropIndex('IDX_record_status', '{{%groups_types}}');
        $this->dropTable('{{%groups_types}}');
    }
}
