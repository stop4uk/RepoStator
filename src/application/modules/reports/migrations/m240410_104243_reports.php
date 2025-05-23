<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m240410_104243_reports extends Migration
{
    const TABLE = '{{%reports}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(11),
            'name' => $this->string(64)->notNull()->defaultValue(''),
            'description' => $this->text()->null()->defaultValue(null),
            'groups_only' => $this->text()->null()->defaultValue(null),
            'groups_required' => $this->text()->null()->defaultValue(null),
            'left_period' => $this->integer(11)->null()->defaultValue(null),
            'block_minutes' => $this->smallInteger(2)->null()->defaultValue(null),
            'null_day' => $this->tinyInteger(1)->notNull()->defaultValue(0),
            'created_at' => $this->integer(11)->notNull(),
            'created_uid' => $this->integer(11)->notNull(),
            'created_gid' => $this->integer(11)->notNull(),
            'updated_at' => $this->integer(11)->null()->defaultValue(null),
            'updated_uid' => $this->integer(11)->null()->defaultValue(null),
            'record_status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createIndex('UQ_name', self::TABLE, ['name'], true);
        $this->createIndex('IDX_created_uid', self::TABLE, ['created_uid'], false);
        $this->createIndex('IDX_created_gid', self::TABLE, ['created_gid'], false);
        $this->createIndex('IDX_record_status', self::TABLE, ['record_status'], false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('UQ_name', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropIndex('IDX_created_gid', self::TABLE);
        $this->dropIndex('IDX_record_status', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
