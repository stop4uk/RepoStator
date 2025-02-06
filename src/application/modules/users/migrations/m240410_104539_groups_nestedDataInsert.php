<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104539_groups_nestedDataInsert extends Migration
{
    const TABLE = '{{%groups_nested}}';

    public function safeUp(): void
    {
        $this->batchInsert(self::TABLE, ["id", "group_id", "lft", "rgt", "depth"], [
            [
                'id' => 1,
                'group_id' => 1,
                'lft' => 0,
                'rgt' => 1,
                'depth' => 0,
            ],
        ]);
    }

    public function safeDown(): void
    {
        $this->truncateTable(self::TABLE . ' CASCADE');
    }
}
