<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104242_queue extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%queue}}',
            [
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
            ],$tableOptions
        );
        $this->createIndex('channel','{{%queue}}',['channel'],false);
        $this->createIndex('reserved_at','{{%queue}}',['reserved_at'],false);
        $this->createIndex('priority','{{%queue}}',['priority'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('channel', '{{%queue}}');
        $this->dropIndex('reserved_at', '{{%queue}}');
        $this->dropIndex('priority', '{{%queue}}');
        $this->dropTable('{{%queue}}');
    }
}
