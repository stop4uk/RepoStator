<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104254_settings extends Migration
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
            '{{%settings}}',
            [
                'category'=> $this->string(24)->notNull(),
                'key'=> $this->string(48)->notNull(),
                'value'=> $this->text()->null()->defaultValue(null),
                'description'=> $this->text()->null()->defaultValue(null),
                'required'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
                'sort'=> $this->integer(2)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->addPrimaryKey('pk_on_settings','{{%settings}}',['category','key']);

    }

    public function safeDown()
    {
    $this->dropPrimaryKey('pk_on_settings','{{%settings}}');
        $this->dropTable('{{%settings}}');
    }
}
