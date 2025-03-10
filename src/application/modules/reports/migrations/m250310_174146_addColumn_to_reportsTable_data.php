<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m250310_174146_addColumn_to_reportsTable_data extends Migration
{
    const TABLE = '{{%reports}}';

    public function safeUp(): void
    {
        $this->addColumn(self::TABLE, 'allow_dynamicForm', $this->integer(1)->defaultValue(0)->after('null_day'));
    }

    public function safeDown(): void
    {
        $this->dropColumn(self::TABLE, 'allow_dynamicForm');
    }
}
