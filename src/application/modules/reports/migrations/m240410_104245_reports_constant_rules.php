<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m240410_104245_reports_constant_rules extends Migration
{
    const TABLE = '{{%reports_constant}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(11),
            'record' => $this->string(32)->notNull()->defaultValue(''),
            'name' => $this->string(64)->notNull()->defaultValue(''),
            'description' => $this->text()->null()->defaultValue(null),
            'rule' => $this->text()->notNull(),
            'report_id' => $this->integer(11)->null()->defaultValue(null),
            'groups_only' => $this->text()->null()->defaultValue(null),
            'created_at' => $this->integer(11)->notNull(),
            'created_uid' => $this->integer(11)->notNull(),
            'created_gid' => $this->integer(11)->null()->defaultValue(null),
            'updated_at' => $this->integer(11)->null()->defaultValue(null),
            'updated_uid' => $this->integer(11)->null()->defaultValue(null),
            'record_status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('UQ_name', self::TABLE, ['name'], true);
        $this->createIndex('UQ_record', self::TABLE, ['record'], true);
        $this->createIndex('IDX_record_status', self::TABLE, ['record_status'], false);
        $this->createIndex('IDX_created_uid', self::TABLE, ['created_uid'], false);
        $this->createIndex('IDX_created_gid', self::TABLE, ['created_gid'], false);
        $this->createIndex('IDX_report_id', self::TABLE, ['report_id'], false);
    }

    public function safeDown(): void
    {
        $this->dropIndex('UQ_name', self::TABLE);
        $this->dropIndex('UQ_record', self::TABLE);
        $this->dropIndex('IDX_record_status', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropIndex('IDX_created_gid', self::TABLE);
        $this->dropIndex('IDX_report_id', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
