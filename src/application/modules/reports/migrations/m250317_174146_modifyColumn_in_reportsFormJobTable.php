<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

final class m250317_174146_modifyColumn_in_reportsFormJobTable extends Migration
{
    const TABLE = '{{%reports_form_jobs}}';

    public function safeUp(): void
    {
        $this->alterColumn(self::TABLE, 'template_id', $this->integer(11)->null()->defaultValue(null));
    }

    public function safeDown(): void
    {
        $this->alterColumn(self::TABLE, 'template_id', $this->integer(11)->notNull());
    }
}
