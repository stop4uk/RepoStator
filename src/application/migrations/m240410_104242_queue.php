<?php

use yii\db\Migration;

final class m240410_104242_queue extends Migration
{
    const TABLE = '{{%queue}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id'=> $this->primaryKey(11),
            'channel'=> $this->string(255)->notNull(),
            'job'=> $this->binary()->notNull(),
            'pushed_at'=> $this->integer(11)->notNull(),
            'ttr'=> $this->integer(11)->notNull(),
            'delay'=> $this->integer(11)->notNull()->defaultValue(0),
            'priority'=> $this->integer(11)->unsigned()->notNull()->defaultValue(1024),
            'reserved_at'=> $this->integer(11)->null()->defaultValue(null),
            'attempt'=> $this->integer(11)->null()->defaultValue(null),
            'done_at'=> $this->integer(11)->null()->defaultValue(null),
        ],$tableOptions);

        $this->createIndex('channel',self::TABLE,['channel'],false);
        $this->createIndex('reserved_at',self::TABLE,['reserved_at'],false);
        $this->createIndex('priority',self::TABLE,['priority'],false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('channel', self::TABLE);
        $this->dropIndex('reserved_at', self::TABLE);
        $this->dropIndex('priority', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
