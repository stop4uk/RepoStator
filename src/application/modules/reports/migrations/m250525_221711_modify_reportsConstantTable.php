<?php

namespace app\modules\reports\migrations;

use yii\db\{
    Migration,
    Expression
};
use yii\helpers\Json;

use app\helpers\CommonHelper;
use app\modules\reports\entities\ReportConstantEntity;

final class m250525_221711_modify_reportsConstantTable extends Migration
{
    const TABLE = '{{%reports_constant}}';

    public function safeUp(): void
    {
        ReportConstantEntity::updateAll(['description' => null], 'description = ""');
        ReportConstantEntity::updateAll(['reports_only' => null], 'reports_only = ""');
        $reports= ReportConstantEntity::findAll(['is not', 'reports_only', new Expression('null')]);
        if ($reports) {
            foreach ($reports as $report) {
                $list = CommonHelper::explodeField($report->reports_only);
                $report->reports_only = Json::encode($list);
                $report->save(false);
            }
        }

        $this->alterColumn(self::TABLE, 'description', $this->json()->null());
        $this->alterColumn(self::TABLE, 'reports_only', $this->json()->null());
    }

    public function safeDown(): void
    {
        $this->alterColumn(self::TABLE, 'reports_only', $this->text()->null()->defaultValue(null));
        $this->alterColumn(self::TABLE, 'description', $this->text()->null()->defaultValue(null));
    }
}
