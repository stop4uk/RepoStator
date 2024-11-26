<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104247_reports_data_changes extends Migration
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
            '{{%reports_data_changes}}',
            [
                'id'=> $this->primaryKey(11),
                'report_id'=> $this->integer(11)->notNull(),
                'data_id'=> $this->integer(11)->notNull(),
                'content'=> $this->text()->notNull(),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
                'created_gid'=> $this->integer(11)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('IDX_report_id','{{%reports_data_changes}}',['report_id'],false);
        $this->createIndex('IDX_data_id','{{%reports_data_changes}}',['data_id'],false);
        $this->createIndex('IDX_created_uid','{{%reports_data_changes}}',['created_uid'],false);
        $this->createIndex('IDX_created_gid','{{%reports_data_changes}}',['created_gid'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('IDX_report_id', '{{%reports_data_changes}}');
        $this->dropIndex('IDX_data_id', '{{%reports_data_changes}}');
        $this->dropIndex('IDX_created_uid', '{{%reports_data_changes}}');
        $this->dropIndex('IDX_created_gid', '{{%reports_data_changes}}');
        $this->dropTable('{{%reports_data_changes}}');
    }
}
