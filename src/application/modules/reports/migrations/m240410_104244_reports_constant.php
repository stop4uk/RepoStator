<?php

use yii\db\Migration;

final class m240410_104244_reports_constant extends Migration
{
    const TABLE = '{{%reports_constant}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(11),
            'record' => $this->string(32)->notNull()->defaultValue(''),
            'name' => $this->string(64)->notNull()->defaultValue(''),
            'name_full' => $this->text()->null()->defaultValue(null),
            'description' => $this->text()->null()->defaultValue(null),
            'union_rules' => $this->text()->null()->defaultValue(null),
            'reports_only' => $this->text()->null()->defaultValue(null),
            'created_at' => $this->integer(11)->notNull(),
            'created_uid' => $this->integer(11)->notNull(),
            'created_gid' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->null()->defaultValue(null),
            'updated_uid' => $this->integer(11)->null()->defaultValue(null),
            'record_status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('UQ_name_record', self::TABLE, ['record'], true);
        $this->createIndex('UQ_name', self::TABLE, ['name'], true);
        $this->createIndex('IDX_record_status', self::TABLE, ['record_status'], false);
        $this->createIndex('IDX_created_uid', self::TABLE, ['created_uid'], false);
        $this->createIndex('IDX_created_gid', self::TABLE, ['created_gid'], false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('UQ_name_record', self::TABLE);
        $this->dropIndex('UQ_name', self::TABLE);
        $this->dropIndex('IDX_record_status', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropIndex('IDX_created_gid', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
