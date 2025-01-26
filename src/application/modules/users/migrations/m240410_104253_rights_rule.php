<?php

use yii\db\Migration;

final class m240410_104253_rights_rule extends Migration
{
    const TABLE = '{{%rights_rule}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'name'=> $this->string(64)->notNull(),
            'data'=> $this->text()->null()->defaultValue(null),
            'created_at'=> $this->integer(11)->notNull(),
            'created_uid'=> $this->integer(11)->null()->defaultValue(null),
            'updated_at'=> $this->integer(11)->null()->defaultValue(null),
            'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
        ],$tableOptions);

        $this->addPrimaryKey('pk_on_rights_rule',self::TABLE,['name']);
    }

    public function safeDown(): void
    {
    $this->dropPrimaryKey('pk_on_rights_rule',self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
