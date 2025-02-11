<?php

namespace app\modules\users\migrations;

use yii\db\Migration;

final class m250211_225824_changeColumn_usersRights_table extends Migration
{
    const TABLE = '{{%users_rights}}';

    public function safeUp(): void
    {
        $this->alterColumn(self::TABLE, 'created_uid', $this->integer()->null()->defaultValue(null));
    }

    public function safeDown(): void
    {
        $this->alterColumn(self::TABLE, 'created_uid', $this->integer()->notNull()->defaultValue(null));
    }
}
