<?php

use yii\db\Migration;

class m240410_104252_rights_item_child extends Migration
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
            '{{%rights_item_child}}',
            [
                'parent'=> $this->string(64)->notNull(),
                'child'=> $this->string(64)->notNull(),
            ],$tableOptions
        );
        $this->createIndex('child','{{%rights_item_child}}',['child'],false);
        $this->addPrimaryKey('pk_on_rights_item_child','{{%rights_item_child}}',['parent','child']);

    }

    public function safeDown()
    {
    $this->dropPrimaryKey('pk_on_rights_item_child','{{%rights_item_child}}');
        $this->dropIndex('child', '{{%rights_item_child}}');
        $this->dropTable('{{%rights_item_child}}');
    }
}
