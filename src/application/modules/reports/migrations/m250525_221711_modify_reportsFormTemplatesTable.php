<?php

namespace app\modules\reports\migrations;

use yii\db\{
    Migration,
    Expression
};
use yii\helpers\Json;

use app\helpers\CommonHelper;
use app\modules\reports\entities\ReportFormTemplateEntity;

final class m250525_221711_modify_reportsFormTemplatesTable extends Migration
{
    const TABLE = '{{%reports_form_templates}}';

    public function safeUp(): void
    {
        ReportFormTemplateEntity::updateAll(['table_rows' => null], 'table_rows = ""');
        ReportFormTemplateEntity::updateAll(['table_columns' => null], 'table_columns = ""');
        $rows = ReportFormTemplateEntity::find()->where(['is not', 'table_rows', new Expression('null')])->all();
        $columns = ReportFormTemplateEntity::find()->where(['is not', 'table_columns', new Expression('null')])->all();
        if ($rows) {
            foreach ($rows as $row) {
                $list = CommonHelper::explodeField($row->table_rows);
                $row->table_rows = Json::encode($list);
                $row->save(false);
            }
        }

        if ($columns) {
            foreach ($columns as $column) {
                $list = CommonHelper::explodeField($column->table_columns);
                $column->table_columns = Json::encode($list);
                $column->save(false);
            }
        }

        $this->alterColumn(self::TABLE, 'table_rows', $this->json()->null());
        $this->alterColumn(self::TABLE, 'table_columns', $this->json()->null());
    }

    public function safeDown(): void
    {
        $this->alterColumn(self::TABLE, 'table_columns', $this->text()->null()->defaultValue(null));
        $this->alterColumn(self::TABLE, 'table_rows', $this->text()->null()->defaultValue(null));
    }
}
