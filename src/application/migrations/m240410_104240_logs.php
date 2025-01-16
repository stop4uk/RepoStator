<?php

use yii\db\Migration;

class m240410_104240_logs extends Migration
{

    public function init(): void
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%logs}}',
            [
                'id'=> $this->bigPrimaryKey(20),
                'level'=> $this->integer(11)->null()->defaultValue(null),
                'category'=> $this->string(255)->null()->defaultValue(null),
                'log_time'=> $this->double()->null()->defaultValue(null),
                'prefix'=> $this->text()->null()->defaultValue(null),
                'message'=> $this->text()->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->createIndex('idx_log_level','{{%logs}}',['level'],false);
        $this->createIndex('idx_log_category','{{%logs}}',['category'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('idx_log_level', '{{%logs}}');
        $this->dropIndex('idx_log_category', '{{%logs}}');
        $this->dropTable('{{%logs}}');
    }
}
