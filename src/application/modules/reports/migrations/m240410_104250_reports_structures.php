<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

class m240410_104250_reports_structures extends Migration
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
            '{{%reports_structures}}',
            [
                'id' => $this->primaryKey(11)->unsigned(),
                'report_id' => $this->integer(11)->notNull(),
                'name' => $this->string(64)->notNull()->defaultValue(''),
                'groups_only' => $this->text()->null()->defaultValue(null),
                'content' => $this->text()->notNull(),
                'use_union_rules' => $this->tinyInteger(1)->notNull()->defaultValue(0),
                'created_at' => $this->integer(11)->notNull(),
                'created_uid' => $this->integer(11)->notNull(),
                'created_gid' => $this->integer(11)->notNull(),
                'updated_at' => $this->integer(11)->null()->defaultValue(null),
                'updated_uid' => $this->integer(11)->null()->defaultValue(null),
                'record_status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            ], $tableOptions
        );
        $this->createIndex('UQ_name', '{{%reports_structures}}', ['name'], true);
        $this->createIndex('IDX_report_id', '{{%reports_structures}}', ['report_id'], false);
        $this->createIndex('IDX_record_status', '{{%reports_structures}}', ['record_status'], false);
        $this->createIndex('IDX_use_union_rules', '{{%reports_structures}}', ['use_union_rules'], false);
        $this->createIndex('IDX_created_uid', '{{%reports_structures}}', ['created_uid'], false);
        $this->createIndex('IDX_created_gid', '{{%reports_structures}}', ['created_gid'], false);

    }

    public function safeDown()
    {
        $this->dropIndex('UQ_name', '{{%reports_structures}}');
        $this->dropIndex('IDX_report_id', '{{%reports_structures}}');
        $this->dropIndex('IDX_record_status', '{{%reports_structures}}');
        $this->dropIndex('IDX_use_union_rules', '{{%reports_structures}}');
        $this->dropIndex('IDX_created_uid', '{{%reports_structures}}');
        $this->dropIndex('IDX_created_gid', '{{%reports_structures}}');
        $this->dropTable('{{%reports_structures}}');
    }
}
