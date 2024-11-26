<?php

use yii\db\Schema;
use yii\db\Migration;

class m240410_104251_rights_item extends Migration
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
            '{{%rights_item}}',
            [
                'name'=> $this->string(64)->notNull(),
                'type'=> $this->string(6)->notNull()->defaultValue(''),
                'description'=> $this->text()->null()->defaultValue(null),
                'rule_name'=> $this->string(64)->null()->defaultValue(null),
                'data'=> $this->binary()->null()->defaultValue(null),
                'created_at'=> $this->integer(11)->notNull(),
                'created_uid'=> $this->integer(11)->notNull(),
                'updated_at'=> $this->integer(11)->null()->defaultValue(null),
                'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->createIndex('IDX_type','{{%rights_item}}',['type'],false);
        $this->createIndex('UQ_name_type','{{%rights_item}}',['name','type'],false);
        $this->addPrimaryKey('pk_on_rights_item','{{%rights_item}}',['name']);

    }

    public function safeDown()
    {
    $this->dropPrimaryKey('pk_on_rights_item','{{%rights_item}}');
        $this->dropIndex('IDX_type', '{{%rights_item}}');
        $this->dropIndex('UQ_name_type', '{{%rights_item}}');
        $this->dropTable('{{%rights_item}}');
    }
}
