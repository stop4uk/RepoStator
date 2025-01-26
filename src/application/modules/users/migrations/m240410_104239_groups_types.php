<?php

use yii\db\Migration;

final class m240410_104239_groups_types extends Migration
{
    const TABLE = '{{%groups_types}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id'=> $this->primaryKey(11),
            'name'=> $this->string(64)->notNull()->defaultValue(''),
            'description'=> $this->text()->null()->defaultValue(null),
            'created_at'=> $this->integer(11)->notNull(),
            'created_uid'=> $this->integer(11)->notNull(),
            'updated_at'=> $this->integer(11)->null()->defaultValue(null),
            'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
            'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
        ],$tableOptions);

        $this->createIndex('UQ_name',self::TABLE,['name'],false);
        $this->createIndex('IDX_record_status',self::TABLE,['record_status'],false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('UQ_name', self::TABLE);
        $this->dropIndex('IDX_record_status', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
