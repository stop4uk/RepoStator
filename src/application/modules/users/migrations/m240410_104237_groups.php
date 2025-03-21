<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104237_groups extends Migration
{
    const TABLE = '{{%groups}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id'=> $this->primaryKey(11),
            'code'=> $this->string(10)->null()->defaultValue(null),
            'name'=> $this->string(64)->notNull()->defaultValue(''),
            'name_full'=> $this->string(255)->null()->defaultValue(null),
            'description'=> $this->text()->null()->defaultValue(null),
            'accept_send'=> $this->tinyInteger(1)->null()->defaultValue(null),
            'type_id'=> $this->integer(11)->null()->defaultValue(null),
            'created_at'=> $this->integer(11)->notNull(),
            'created_uid'=> $this->integer(11)->notNull(),
            'updated_at'=> $this->integer(11)->null()->defaultValue(null),
            'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
            'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
        ],$tableOptions);

        $this->createIndex('UQ_name',self::TABLE,['name'],true);
        $this->createIndex('UQ_code',self::TABLE,['code'],true);
        $this->createIndex('IDX_accept_send',self::TABLE,['accept_send'],false);
        $this->createIndex('IDX_type_id',self::TABLE,['type_id'],false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('UQ_name', self::TABLE);
        $this->dropIndex('UQ_code', self::TABLE);
        $this->dropIndex('IDX_accept_send', self::TABLE);
        $this->dropIndex('IDX_type_id', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
