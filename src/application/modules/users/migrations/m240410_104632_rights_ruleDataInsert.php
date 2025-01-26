<?php

use yii\db\Migration;

final class m240410_104632_rights_ruleDataInsert extends Migration
{
    const TABLE = '{{%rights_rule}}';

    public function safeUp(): void
    {
        $this->batchInsert(self::TABLE, ["name", "data", "created_at", "created_uid", "updated_at", "updated_uid"], [
            [
                'name' => 'checkAll',
                'data' => 'O:27:"app\\rbac\\rules\\CheckAllRule":3:{s:4:"name";s:8:"checkAll";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkDataAll',
                'data' => 'O:31:"app\\rbac\\rules\\CheckDataAllRule":3:{s:4:"name";s:12:"checkDataAll";s:9:"createdAt";i:1712652656;s:9:"updatedAt";i:1712652656;}',
                'created_at' => 1712652656,
                'created_uid' => 1,
                'updated_at' => 1712652656,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkDeleteAll',
                'data' => 'O:33:"app\\rbac\\rules\\CheckDeleteAllRule":3:{s:4:"name";s:14:"checkDeleteAll";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkDeleteGroup',
                'data' => 'O:35:"app\\rbac\\rules\\CheckDeleteGroupRule":3:{s:4:"name";s:16:"checkDeleteGroup";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkDeleteMain',
                'data' => 'O:34:"app\\rbac\\rules\\CheckDeleteMainRule":3:{s:4:"name";s:15:"checkDeleteMain";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkGroup',
                'data' => 'O:29:"app\\rbac\\rules\\CheckGroupRule":3:{s:4:"name";s:10:"checkGroup";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkMain',
                'data' => 'O:28:"app\\rbac\\rules\\CheckMainRule":3:{s:4:"name";s:9:"checkMain";s:9:"createdAt";i:1712579846;s:9:"updatedAt";i:1712579846;}',
                'created_at' => 1712579846,
                'created_uid' => 1,
                'updated_at' => 1712579846,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkUserAll',
                'data' => 'O:31:"app\\rbac\\rules\\CheckUserAllRule":3:{s:4:"name";s:12:"checkUserAll";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkUserDeleteAll',
                'data' => 'O:37:"app\\rbac\\rules\\CheckUserDeleteAllRule":3:{s:4:"name";s:18:"checkUserDeleteAll";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkUserDeleteGroup',
                'data' => 'O:39:"app\\rbac\\rules\\CheckUserDeleteGroupRule":3:{s:4:"name";s:20:"checkUserDeleteGroup";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
            [
                'name' => 'checkUserGroup',
                'data' => 'O:33:"app\\rbac\\rules\\CheckUserGroupRule":3:{s:4:"name";s:14:"checkUserGroup";s:9:"createdAt";i:1712579953;s:9:"updatedAt";i:1712579953;}',
                'created_at' => 1712579953,
                'created_uid' => 1,
                'updated_at' => 1712579953,
                'updated_uid' => null,
            ],
        ]);
    }

    public function safeDown(): void
    {
        $this->truncateTable(self::TABLE . ' CASCADE');
    }
}
