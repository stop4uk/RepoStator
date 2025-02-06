<?php

use yii\db\Migration;

final class m240410_104254_settings extends Migration
{
    const TABLE = '{{%settings}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'category'=> $this->string(24)->notNull(),
            'key'=> $this->string(48)->notNull(),
            'value'=> $this->text()->null()->defaultValue(null),
            'description'=> $this->text()->null()->defaultValue(null),
            'required'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
            'sort'=> $this->integer(2)->null()->defaultValue(null),
        ],$tableOptions);

        $this->addPrimaryKey('pk_on_settings',self::TABLE, ['category','key']);

    }

    public function safeDown(): void
    {
        $this->dropPrimaryKey('pk_on_settings',self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
