<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104243_reports extends Migration
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
            '{{%reports}}',
            [
                'id'=> $this->primaryKey(11),
                'name'=> $this->string(64)->notNull()->defaultValue(''),
                'description'=> $this->text()->null()->defaultValue(null),
                'groups_only'=> $this->text()->null()->defaultValue(null),
                'groups_required'=> $this->text()->null()->defaultValue(null),
                'left_period'=> $this->integer(11)->null()->defaultValue(null),
                'block_minutes'=> $this->smallInteger(2)->null()->defaultValue(null),
                'null_day'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
                'created_gid'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
                'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('UQ_name','{{%reports}}',['name'],true);
        $this->createIndex('IDX_created_uid','{{%reports}}',['created_uid'],false);
        $this->createIndex('IDX_created_gid','{{%reports}}',['created_gid'],false);
        $this->createIndex('IDX_record_status','{{%reports}}',['record_status'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('UQ_name', '{{%reports}}');
        $this->dropIndex('IDX_created_uid', '{{%reports}}');
        $this->dropIndex('IDX_created_gid', '{{%reports}}');
        $this->dropIndex('IDX_record_status', '{{%reports}}');
        $this->dropTable('{{%reports}}');
    }
}
