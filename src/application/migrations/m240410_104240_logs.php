<?php

use yii\db\Migration;

final class m240410_104240_logs extends Migration
{
    const TABLE =  '{{%logs}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id'=> $this->bigPrimaryKey(20),
            'level'=> $this->integer(11)->null()->defaultValue(null),
            'category'=> $this->string(255)->null()->defaultValue(null),
            'log_time'=> $this->double()->null()->defaultValue(null),
            'prefix'=> $this->text()->null()->defaultValue(null),
            'message'=> $this->text()->null()->defaultValue(null),
        ], $tableOptions);

        $this->createIndex('idx_log_level',self::TABLE,['level'],false);
        $this->createIndex('idx_log_category',self::TABLE,['category'],false);
    }

    public function safeDown(): void
    {
        $this->dropIndex('idx_log_level', self::TABLE);
        $this->dropIndex('idx_log_category', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
