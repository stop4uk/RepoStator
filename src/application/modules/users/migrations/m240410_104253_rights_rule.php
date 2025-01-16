<?php

use yii\db\Migration;

class m240410_104253_rights_rule extends Migration
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
            '{{%rights_rule}}',
            [
                'name'=> $this->string(64)->notNull(),
                'data'=> $this->text()->null()->defaultValue(null),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->null()->defaultValue(null),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->addPrimaryKey('pk_on_rights_rule','{{%rights_rule}}',['name']);

    }

    public function safeDown()
    {
    $this->dropPrimaryKey('pk_on_rights_rule','{{%rights_rule}}');
        $this->dropTable('{{%rights_rule}}');
    }
}
