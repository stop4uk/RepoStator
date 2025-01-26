<?php

use yii\db\Migration;

final class m240410_104251_rights_item extends Migration
{
    const TABLE = '{{%rights_item}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'name'=> $this->string(64)->notNull(),
            'type'=> $this->string(6)->notNull()->defaultValue(''),
            'description'=> $this->text()->null()->defaultValue(null),
            'rule_name'=> $this->string(64)->null()->defaultValue(null),
            'data'=> $this->binary()->null()->defaultValue(null),
            'created_at'=> $this->integer(11)->notNull(),
            'created_uid'=> $this->integer(11)->notNull(),
            'updated_at'=> $this->integer(11)->null()->defaultValue(null),
            'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
        ],$tableOptions);

        $this->createIndex('IDX_type',self::TABLE,['type'],false);
        $this->createIndex('UQ_name_type',self::TABLE,['name','type'],false);
        $this->addPrimaryKey('pk_on_rights_item',self::TABLE,['name']);
    }

    public function safeDown(): void
    {
        $this->dropPrimaryKey('pk_on_rights_item',self::TABLE);
        $this->dropIndex('IDX_type', self::TABLE);
        $this->dropIndex('UQ_name_type', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
