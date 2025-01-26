<?php

use yii\db\Migration;

final class m240410_104246_reports_data extends Migration
{
    const TABLE = '{{%reports_data}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(11),
            'report_id' => $this->integer(11)->notNull(),
            'report_datetime' => $this->integer(11)->notNull(),
            'group_id' => $this->integer(11)->notNull(),
            'struct_id' => $this->integer(11)->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->integer(11)->notNull(),
            'created_uid' => $this->integer(11)->notNull(),
            'created_gid' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->null()->defaultValue(null),
            'updated_uid' => $this->integer(11)->null()->defaultValue(null),
            'record_status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('IDX_report_id', self::TABLE, ['report_id'], false);
        $this->createIndex('IDX_group_id', self::TABLE, ['group_id'], false);
        $this->createIndex('IDX_struct_id', self::TABLE, ['struct_id'], false);
        $this->createIndex('IDX_record_status', self::TABLE, ['record_status'], false);
        $this->createIndex('IDX_report_datetime', self::TABLE, ['report_datetime'], false);
        $this->createIndex('IDX_created_uid', self::TABLE, ['created_uid'], false);
        $this->createIndex('IDX_created_gid', self::TABLE, ['created_gid'], false);
    }

    public function safeDown(): void
    {
        $this->dropIndex('IDX_report_id', self::TABLE);
        $this->dropIndex('IDX_group_id', self::TABLE);
        $this->dropIndex('IDX_struct_id', self::TABLE);
        $this->dropIndex('IDX_record_status', self::TABLE);
        $this->dropIndex('IDX_report_datetime', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropIndex('IDX_created_gid', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
