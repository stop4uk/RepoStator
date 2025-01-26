<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m240410_104248_reports_form_jobs extends Migration
{
    const TABLE = '{{%reports_form_jobs}}';

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
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
        ], $tableOptions);

        $this->createIndex('UQ_job_id', self::TABLE, ['job_id'], true);
        $this->createIndex('IDX_job_status', self::TABLE, ['job_status'], false);
        $this->createIndex('IDX_report_id', self::TABLE, ['report_id'], false);
        $this->createIndex('IDX_template_id', self::TABLE, ['template_id'], false);
        $this->createIndex('IDX_created_uid', self::TABLE, ['created_uid'], false);
        $this->createIndex('IDX_created_gid', self::TABLE, ['created_gid'], false);
    }

    public function safeDown(): void
    {
        $this->dropIndex('UQ_job_id', self::TABLE);
        $this->dropIndex('IDX_job_status', self::TABLE);
        $this->dropIndex('IDX_report_id', self::TABLE);
        $this->dropIndex('IDX_template_id', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropIndex('IDX_created_gid', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
