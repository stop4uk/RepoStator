<?php

use yii\db\Migration;

final class m240410_104504_usersDataInsert extends Migration
{
    const TABLE = '{{%users}}';

    public function safeUp(): void
    {
        $this->batchInsert(self::TABLE, ["id", "email", "password", "lastname", "firstname", "middlename", "phone", "account_status", "account_key", "account_cpass_required", "created_at", "created_uid", "updated_at", "updated_uid", "blocked_at", "blocked_uid", "blocked_comment", "record_status"], [
            [
                'id' => 1,
                'email' => 'admin@admin.loc',
                'password' => '$2y$13$1nWUw0czIbguopudvWERF.TMImRumHTd9PEpBg30CI5Z6QqRnMhN2',
                'lastname' => 'Главный',
                'firstname' => 'Администратор',
                'middlename' => null,
                'phone' => '1111111111',
                'account_status' => 1,
                'account_key' => 'VMsE9XBb_vN9xTpxJK_jWLiZAWFDTMuL',
                'account_cpass_required' => 0,
                'created_at' => 1708349986,
                'created_uid' => null,
                'updated_at' => null,
                'updated_uid' => null,
                'blocked_at' => null,
                'blocked_uid' => null,
                'blocked_comment' => null,
                'record_status' => 1,
            ],
        ]);
    }

    public function safeDown(): void
    {
        $this->truncateTable(self::TABLE . " CASCADE");
    }
}
