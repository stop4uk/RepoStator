<?php

use yii\db\Schema;
use yii\db\Migration;

class m240703_133514_settingsDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%settings}}', ["category", "key", "value", "description", "required", "sort"], [
            [
                'category' => 'report',
                'key' => 'notification_tComplete',
                'value' => '1',
                'description' => 'Отправлять уведомление о готовности отчета',
                'required' => 0,
                'sort' => 5,
            ],
            [
                'category' => 'report',
                'key' => 'notification_tError',
                'value' => '1',
                'description' => 'Отправлять уведомление об ошибке формирования',
                'required' => 0,
                'sort' => 5,
            ],
        ]);
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%settings}} CASCADE');
    }
}
