<?php

namespace app\modules\reports\migrations;

use yii\db\Migration;

class m240410_104245_reports_constant_rules extends Migration
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
            '{{%reports_constant_rules}}',
            [
                'id' => $this->primaryKey(11),
                'record' => $this->string(32)->notNull()->defaultValue(''),
                'name' => $this->string(64)->notNull()->defaultValue(''),
                'description' => $this->text()->null()->defaultValue(null),
                'rule' => $this->text()->notNull(),
                'report_id' => $this->integer(11)->null()->defaultValue(null),
                'groups_only' => $this->text()->null()->defaultValue(null),
                'created_at' => $this->integer(11)->notNull(),
                'created_uid' => $this->integer(11)->notNull(),
                'created_gid' => $this->integer(11)->null()->defaultValue(null),
                'updated_at' => $this->integer(11)->null()->defaultValue(null),
                'updated_uid' => $this->integer(11)->null()->defaultValue(null),
                'record_status' => $this->tinyInteger(1)->notNull()->defaultValue(1),
            ], $tableOptions
        );
        $this->createIndex('UQ_name', '{{%reports_constant_rules}}', ['name'], true);
        $this->createIndex('UQ_record', '{{%reports_constant_rules}}', ['record'], true);
        $this->createIndex('IDX_record_status', '{{%reports_constant_rules}}', ['record_status'], false);
        $this->createIndex('IDX_created_uid', '{{%reports_constant_rules}}', ['created_uid'], false);
        $this->createIndex('IDX_created_gid', '{{%reports_constant_rules}}', ['created_gid'], false);
        $this->createIndex('IDX_report_id', '{{%reports_constant_rules}}', ['report_id'], false);

    }

    public function safeDown()
    {
        $this->dropIndex('UQ_name', '{{%reports_constant_rules}}');
        $this->dropIndex('UQ_record', '{{%reports_constant_rules}}');
        $this->dropIndex('IDX_record_status', '{{%reports_constant_rules}}');
        $this->dropIndex('IDX_created_uid', '{{%reports_constant_rules}}');
        $this->dropIndex('IDX_created_gid', '{{%reports_constant_rules}}');
        $this->dropIndex('IDX_report_id', '{{%reports_constant_rules}}');
        $this->dropTable('{{%reports_constant_rules}}');
    }
}
