<?php

use yii\db\Migration;

class m240410_104238_groups_nested extends Migration
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
            '{{%groups_nested}}',
            [
                'id'=> $this->primaryKey(11)->unsigned(),
                'group_id'=> $this->integer(11)->notNull(),
                'lft'=> $this->integer(11)->notNull(),
                'rgt'=> $this->integer(11)->notNull(),
                'depth'=> $this->integer(11)->notNull()->defaultValue(0),
            ],$tableOptions
        );
        $this->createIndex('IDX_group_id','{{%groups_nested}}',['group_id'],false);
        $this->createIndex('IDX_lft','{{%groups_nested}}',['lft'],false);
        $this->createIndex('IDX_rgt','{{%groups_nested}}',['rgt'],false);
        $this->createIndex('IDX_depth','{{%groups_nested}}',['depth'],false);

    }

    public function safeDown()
    {
        $this->dropIndex('IDX_group_id', '{{%groups_nested}}');
        $this->dropIndex('IDX_lft', '{{%groups_nested}}');
        $this->dropIndex('IDX_rgt', '{{%groups_nested}}');
        $this->dropIndex('IDX_depth', '{{%groups_nested}}');
        $this->dropTable('{{%groups_nested}}');
    }
}
