<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104238_groups_nested extends Migration
{
    const TABLE = '{{%groups_nested}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE,  [
            'id'=> $this->primaryKey(11)->unsigned(),
            'group_id'=> $this->integer(11)->notNull(),
            'lft'=> $this->integer(11)->notNull(),
            'rgt'=> $this->integer(11)->notNull(),
            'depth'=> $this->integer(11)->notNull()->defaultValue(0),
        ],$tableOptions);

        $this->createIndex('IDX_group_id',self::TABLE,['group_id'],false);
        $this->createIndex('IDX_lft',self::TABLE,['lft'],false);
        $this->createIndex('IDX_rgt',self::TABLE,['rgt'],false);
        $this->createIndex('IDX_depth',self::TABLE,['depth'],false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('IDX_group_id', self::TABLE);
        $this->dropIndex('IDX_lft', self::TABLE);
        $this->dropIndex('IDX_rgt', self::TABLE);
        $this->dropIndex('IDX_depth', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
