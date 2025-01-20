<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

class m240410_104248_reports_form_jobs extends Migration
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
            '{{%reports_form_jobs}}',
            [
                'id' => $this->primaryKey(11),
                'job_id' => $this->string(32)->notNull(),
                'job_status' => $this->tinyInteger(1)->notNull()->defaultValue(0),
                'report_id' => $this->integer(11)->notNull(),
                'template_id' => $this->integer(11)->notNull(),
                'form_period' => $this->string(30)->notNull()->defaultValue(''),
                'file' => $this->string(255)->null()->defaultValue(null),
                'created_at' => $this->integer(11)->notNull(),
                'created_uid' => $this->integer(11)->null()->defaultValue(null),
                'created_gid' => $this->integer(11)->null()->defaultValue(null),
                'updated_at' => $this->integer(11)->null()->defaultValue(null),
            ], $tableOptions
        );
        $this->createIndex('UQ_job_id', '{{%reports_form_jobs}}', ['job_id'], true);
        $this->createIndex('IDX_job_status', '{{%reports_form_jobs}}', ['job_status'], false);
        $this->createIndex('IDX_report_id', '{{%reports_form_jobs}}', ['report_id'], false);
        $this->createIndex('IDX_template_id', '{{%reports_form_jobs}}', ['template_id'], false);
        $this->createIndex('IDX_created_uid', '{{%reports_form_jobs}}', ['created_uid'], false);
        $this->createIndex('IDX_created_gid', '{{%reports_form_jobs}}', ['created_gid'], false);

    }

    public function safeDown()
    {
        $this->dropIndex('UQ_job_id', '{{%reports_form_jobs}}');
        $this->dropIndex('IDX_job_status', '{{%reports_form_jobs}}');
        $this->dropIndex('IDX_report_id', '{{%reports_form_jobs}}');
        $this->dropIndex('IDX_template_id', '{{%reports_form_jobs}}');
        $this->dropIndex('IDX_created_uid', '{{%reports_form_jobs}}');
        $this->dropIndex('IDX_created_gid', '{{%reports_form_jobs}}');
        $this->dropTable('{{%reports_form_jobs}}');
    }
}
