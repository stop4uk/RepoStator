<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m250211_155112_changeIndex_groups_table extends Migration
{
    const TABLE = '{{%groups}}';

    public function safeUp(): void
    {
        $this->dropIndex('UQ_code', self::TABLE);
        $this->createIndex('IDX_code', self::TABLE, ['code']);
    }

    public function safeDown(): void
    {
        $this->dropIndex('IDX_code', self::TABLE);
        $this->createIndex('UQ_code', self::TABLE, ['code'], true);
    }
}
