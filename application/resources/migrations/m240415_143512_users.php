<?php

use yii\db\Migration;

class m240415_143512_users extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->alterColumn('{{%users}}', 'phone', $this->string(10)->null()->defaultValue(null));
        $this->createIndex('IDX_phone','{{%users}}',['phone'],false);
    }

    public function safeDown()
    {
        $this->dropIndex('UQ_phone', '{{%users}}');
    }
}
