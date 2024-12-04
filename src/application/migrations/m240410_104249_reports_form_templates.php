<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104249_reports_form_templates extends Migration
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
            '{{%reports_form_templates}}',
            [
                'id'=> $this->primaryKey(11),
                'report_id'=> $this->integer(11)->notNull(),
                'name'=> $this->string(64)->notNull()->defaultValue(''),
                'form_datetime'=> $this->tinyInteger(1)->null()->defaultValue(0),
                'form_type'=> $this->tinyInteger(1)->null()->defaultValue(0),
                'form_usejobs'=> $this->tinyInteger(1)->null()->defaultValue(0),
                'use_appg'=> $this->tinyInteger(1)->null()->defaultValue(0),
                'use_grouptype'=> $this->tinyInteger(1)->null()->defaultValue(0),
                'table_type'=> $this->tinyInteger(1)->null()->defaultValue(0),
                'table_rows'=> $this->text()->null()->defaultValue(null),
                'table_columns'=> $this->text()->null()->defaultValue(null),
                'limit_maxfiles'=> $this->integer(4)->null()->defaultValue(100),
                'limit_maxsavetime'=> $this->integer(11)->null()->defaultValue(864000),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
                'created_gid'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
                'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
            ],$tableOptions
        );
        $this->createIndex('UQ_name','{{%reports_form_templates}}',['name'],true);
        $this->createIndex('IDX_report_id','{{%reports_form_templates}}',['report_id'],false);
        $this->createIndex('IDX_record_status','{{%reports_form_templates}}',['record_status'],false);
        $this->createIndex('IDX_form_type','{{%reports_form_templates}}',['form_type'],false);
        $this->createIndex('IDX_form_usejobs','{{%reports_form_templates}}',['form_usejobs'],false);
        $this->createIndex('IDX_use_appg','{{%reports_form_templates}}',['use_appg'],false);
        $this->createIndex('IDX_use_grouptype','{{%reports_form_templates}}',['use_grouptype'],false);
        $this->createIndex('IDX_created_uid','{{%reports_form_templates}}',['created_uid'],false);
        $this->createIndex('IDX_created_gid','{{%reports_form_templates}}',['created_gid'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('UQ_name', '{{%reports_form_templates}}');
        $this->dropIndex('IDX_report_id', '{{%reports_form_templates}}');
        $this->dropIndex('IDX_record_status', '{{%reports_form_templates}}');
        $this->dropIndex('IDX_form_type', '{{%reports_form_templates}}');
        $this->dropIndex('IDX_form_usejobs', '{{%reports_form_templates}}');
        $this->dropIndex('IDX_use_appg', '{{%reports_form_templates}}');
        $this->dropIndex('IDX_use_grouptype', '{{%reports_form_templates}}');
        $this->dropIndex('IDX_created_uid', '{{%reports_form_templates}}');
        $this->dropIndex('IDX_created_gid', '{{%reports_form_templates}}');
        $this->dropTable('{{%reports_form_templates}}');
    }
}
