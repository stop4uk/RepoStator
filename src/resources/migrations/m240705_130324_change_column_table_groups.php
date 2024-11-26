<?php

use yii\db\Migration;

class m240705_130324_change_column_table_groups extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->alterColumn('{{%groups}}', 'code', $this->string(10)->null()->defaultValue(null));
    }

    public function safeDown()
    {
        $this->alterColumn('{{%groups}}', 'code', $this->string(6)->null()->defaultValue(null));
    }
}
