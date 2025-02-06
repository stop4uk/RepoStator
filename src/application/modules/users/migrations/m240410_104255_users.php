<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m240410_104255_users extends Migration
{
    const TABLE = '{{%users}}';

    public function safeUp(): void
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(self::TABLE, [
            'id'=> $this->primaryKey(11)->unsigned(),
            'email'=> $this->string(58)->notNull()->defaultValue(''),
            'password'=> $this->string(64)->notNull()->defaultValue(''),
            'lastname'=> $this->string(28)->notNull()->defaultValue(''),
            'firstname'=> $this->string(24)->notNull()->defaultValue(''),
            'middlename'=> $this->string(24)->null()->defaultValue(null),
            'phone'=> $this->string(10)->null()->defaultValue(null),
            'account_status'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
            'account_key'=> $this->string(32)->notNull()->defaultValue(''),
            'account_cpass_required'=> $this->tinyInteger(1)->notNull()->defaultValue(0),
            'created_at'=> $this->integer(11)->notNull(),
            'created_uid'=> $this->integer(11)->null()->defaultValue(null),
            'updated_at'=> $this->integer(11)->null()->defaultValue(null),
            'updated_uid'=> $this->integer(11)->null()->defaultValue(null),
            'blocked_at'=> $this->integer(11)->null()->defaultValue(null),
            'blocked_uid'=> $this->integer(11)->null()->defaultValue(null),
            'blocked_comment'=> $this->text()->null()->defaultValue(null),
            'record_status'=> $this->tinyInteger(1)->notNull()->defaultValue(1),
        ],$tableOptions);

        $this->createIndex('UQ_email',self::TABLE,['email'],true);
        $this->createIndex('UQ_account_key',self::TABLE,['account_key'],true);
        $this->createIndex('UQ_phone',self::TABLE,['phone'],true);
        $this->createIndex('IDX_account_status',self::TABLE,['account_status'],false);
        $this->createIndex('IDX_record_status',self::TABLE,['record_status'],false);
        $this->createIndex('IDX_created_uid',self::TABLE,['created_uid'],false);

    }

    public function safeDown(): void
    {
        $this->dropIndex('UQ_email', self::TABLE);
        $this->dropIndex('UQ_account_key', self::TABLE);
        $this->dropIndex('UQ_phone', self::TABLE);
        $this->dropIndex('IDX_account_status', self::TABLE);
        $this->dropIndex('IDX_record_status', self::TABLE);
        $this->dropIndex('IDX_created_uid', self::TABLE);
        $this->dropTable(self::TABLE);
    }
}
