<?php

namespace app\useCases\reports\migrations;

use yii\db\Migration;

class m240410_104244_reports_constant extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%reports_constant}}',
            [
                'id' => $this->primaryKey(11),
                'record' => $this->string(32)->notNull()->defaultValue(''),
                'name' => $this->string(64)->notNull()->defaultValue(''),
                'name_full' => $this->text()->null()->defaultValue(null),
                'description' => $this->text()->null()->defaultValue(null),
                'union_rules' => $this->text()->null()->defaultValue(null),
                'reports_only' => $this->text()->null()->defaultValue(null),
                'created_at' => $this->integer(11)->notNull(),
                'created_uid' => $this->integer(11)->notNull(),
                'created_gid' => $this->integer(11)->notNull(),
                'updated_at' => $this->integer(11)->null()->defaultValue(null),
                'updated_uid' => $this->integer(11)->null()->defaultValue(null),
                'record_status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            ], $tableOptions
        );
        $this->createIndex('UQ_name_record', '{{%reports_constant}}', ['record'], true);
        $this->createIndex('UQ_name', '{{%reports_constant}}', ['name'], true);
        $this->createIndex('IDX_record_status', '{{%reports_constant}}', ['record_status'], false);
        $this->createIndex('IDX_created_uid', '{{%reports_constant}}', ['created_uid'], false);
        $this->createIndex('IDX_created_gid', '{{%reports_constant}}', ['created_gid'], false);

    }

    public function safeDown()
    {
        $this->dropIndex('UQ_name_record', '{{%reports_constant}}');
        $this->dropIndex('UQ_name', '{{%reports_constant}}');
        $this->dropIndex('IDX_record_status', '{{%reports_constant}}');
        $this->dropIndex('IDX_created_uid', '{{%reports_constant}}');
        $this->dropIndex('IDX_created_gid', '{{%reports_constant}}');
        $this->dropTable('{{%reports_constant}}');
    }
}
