<?php

use yii\db\Migration;

class m240410_104539_groups_nestedDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%groups_nested}}',
                           ["id", "group_id", "lft", "rgt", "depth"],
                            [
    [
        'id' => 1,
        'group_id' => 1,
        'lft' => 0,
        'rgt' => 1,
        'depth' => 0,
    ],
]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%groups_nested}} CASCADE');
    }
}
