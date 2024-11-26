<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104246_reports_data extends Migration
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
            '{{%reports_data}}',
            [
                'id'=> $this->primaryKey(11),
                'report_id'=> $this->integer(11)->notNull(),
                'report_datetime'=> $this->integer(11)->notNull(),
                'group_id'=> $this->integer(11)->notNull(),
                'struct_id'=> $this->integer(11)->notNull(),
                'content'=> $this->text()->notNull(),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
                'created_gid'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
                'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('IDX_report_id','{{%reports_data}}',['report_id'],false);
        $this->createIndex('IDX_group_id','{{%reports_data}}',['group_id'],false);
        $this->createIndex('IDX_struct_id','{{%reports_data}}',['struct_id'],false);
        $this->createIndex('IDX_record_status','{{%reports_data}}',['record_status'],false);
        $this->createIndex('IDX_report_datetime','{{%reports_data}}',['report_datetime'],false);
        $this->createIndex('IDX_created_uid','{{%reports_data}}',['created_uid'],false);
        $this->createIndex('IDX_created_gid','{{%reports_data}}',['created_gid'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('IDX_report_id', '{{%reports_data}}');
        $this->dropIndex('IDX_group_id', '{{%reports_data}}');
        $this->dropIndex('IDX_struct_id', '{{%reports_data}}');
        $this->dropIndex('IDX_record_status', '{{%reports_data}}');
        $this->dropIndex('IDX_report_datetime', '{{%reports_data}}');
        $this->dropIndex('IDX_created_uid', '{{%reports_data}}');
        $this->dropIndex('IDX_created_gid', '{{%reports_data}}');
        $this->dropTable('{{%reports_data}}');
    }
}
