<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m250521_162346_addColumn_in_reportsTable extends Migration
{
    const TABLE = '{{%reports}}';

    public function safeUp(): void
    {
        $this->addColumn(self::TABLE, 'allow_dynamicForm', $this->tinyInteger(1)->defaultValue(0)->after('null_day'));
    }

    public function safeDown(): void
    {
        $this->dropColumn(self::TABLE, 'allow_dynamicForm');
    }
}
