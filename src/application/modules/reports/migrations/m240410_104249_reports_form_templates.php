<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m240410_104249_reports_form_templates extends Migration
{
    const TABLE = '{{%reports_form_templates}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(11),
            'report_id' => $this->integer(11)->notNull(),
            'name' => $this->string(64)->notNull()->defaultValue(''),
            'form_datetime' => $this->tinyInteger(1)->null()->defaultValue(0),
            'form_type' => $this->tinyInteger(1)->null()->defaultValue(0),
            'form_usejobs' => $this->tinyInteger(1)->null()->defaultValue(0),
            'use_appg' => $this->tinyInteger(1)->null()->defaultValue(0),
            'use_grouptype' => $this->tinyInteger(1)->null()->defaultValue(0),
            'table_type' => $this->tinyInteger(1)->null()->defaultValue(0),
            'table_rows' => $this->text()->null()->defaultValue(null),
            'table_columns' => $this->text()->null()->defaultValue(null),
            'limit_maxfiles' => $this->integer(4)->null()->defaultValue(100),
            'limit_maxsavetime' => $this->integer(11)->null()->defaultValue(864000),
            'created_at' => $this->integer(11)->notNull(),
            'created_uid' => $this->integer(11)->notNull(),
            'created_gid' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->null()->defaultValue(null),
            'updated_uid' => $this->integer(11)->null()->defaultValue(null),
            'record_status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('UQ_name', self::TABLE, ['name'], true);
        $this->createIndex('IDX_report_id', self::TABLE, ['report_id'], false);
        $this->createIndex('IDX_record_status', self::TABLE, ['record_status'], false);
        $this->createIndex('IDX_form_type', self::TABLE, ['form_type'], false);
        $this->createIndex('IDX_form_usejobs', self::TABLE, ['form_usejobs'], false);
        $this->createIndex('IDX_use_appg', self::TABLE, ['use_appg'], false);
        $this->createIndex('IDX_use_grouptype', self::TABLE, ['use_grouptype'], false);
        $this->createIndex('IDX_created_uid', self::TABLE, ['created_uid'], false);
        $this->createIndex('IDX_created_gid', self::TABLE, ['created_gid'], false);
    }

    public function safeDown(): void
    {
        $this->dropIndex('UQ_name', self::TABLE);
        $this->dropIndex('IDX_report_id', self::TABLE);
        $this->dropIndex('IDX_record_status', self::TABLE);
        $this->dropIndex('IDX_form_type', self::TABLE);
        $this->dropIndex('IDX_form_usejobs', self::TABLE);
        $this->dropIndex('IDX_use_appg', self::TABLE);
        $this->dropIndex('IDX_use_grouptype', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropIndex('IDX_created_gid', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
