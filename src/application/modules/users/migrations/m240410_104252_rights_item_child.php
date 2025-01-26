<?php

use yii\db\Migration;

final class m240410_104252_rights_item_child extends Migration
{
    const TABLE = '{{%rights_item_child}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'parent'=> $this->string(64)->notNull(),
            'child'=> $this->string(64)->notNull(),
        ],$tableOptions);

        $this->createIndex('child',self::TABLE,['child'],false);
        $this->addPrimaryKey('pk_on_rights_item_child',self::TABLE,['parent','child']);
    }

    public function safeDown(): void
    {
        $this->dropPrimaryKey('pk_on_rights_item_child',self::TABLE);
        $this->dropIndex('child', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
