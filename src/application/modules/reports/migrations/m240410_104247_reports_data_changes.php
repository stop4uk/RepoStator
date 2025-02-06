<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m240410_104247_reports_data_changes extends Migration
{
    const TABLE = '{{%reports_data_changes}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id' => $this->primaryKey(11),
            'report_id' => $this->integer(11)->notNull(),
            'data_id' => $this->integer(11)->notNull(),
            'content' => $this->text()->notNull(),
            'created_at' => $this->integer(11)->notNull(),
            'created_uid' => $this->integer(11)->notNull(),
            'created_gid' => $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createIndex('IDX_report_id', self::TABLE, ['report_id'], false);
        $this->createIndex('IDX_data_id', self::TABLE, ['data_id'], false);
        $this->createIndex('IDX_created_uid', self::TABLE, ['created_uid'], false);
        $this->createIndex('IDX_created_gid', self::TABLE, ['created_gid'], false);
    }

    public function safeDown(): void
    {
        $this->dropIndex('IDX_report_id', self::TABLE);
        $this->dropIndex('IDX_data_id', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropIndex('IDX_created_gid', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
