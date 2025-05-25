<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m250525_221711_modify_reportsDataTable extends Migration
{
    const TABLE = '{{%reports_data}}';

    public function safeUp(): void
    {
        $this->alterColumn(self::TABLE, 'content', $this->json()->notNull());
    }

    public function safeDown(): void
    {
        $this->alterColumn(self::TABLE, 'content', $this->text()->notNull());
    }
}
