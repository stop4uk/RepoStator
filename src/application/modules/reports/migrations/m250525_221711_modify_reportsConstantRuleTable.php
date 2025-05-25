<?php

namespace app\modules\reports\migrations;

use yii\db\{
    Migration,
    Expression
};
use yii\helpers\Json;

use app\helpers\CommonHelper;
use app\modules\reports\entities\ReportConstantRuleEntity;

final class m250525_221711_modify_reportsConstantRuleTable extends Migration
{
    const TABLE = '{{%reports_constant_rules}}';

    public function safeUp(): void
    {
        ReportConstantRuleEntity::updateAll(['description' => null], 'description = ""');
        ReportConstantRuleEntity::updateAll(['groups_only' => null], 'groups_only = ""');
        $groups = ReportConstantRuleEntity::findAll(['is not', 'groups_only', new Expression('null')]);
        if ($groups) {
            foreach ($groups as $group) {
                $list = CommonHelper::explodeField($group->groups_only);
                $group->groups_only = Json::encode($list);
                $group->save(false);
            }
        }

        $this->alterColumn(self::TABLE, 'description', $this->json()->null()->defaultValue(null));
        $this->alterColumn(self::TABLE, 'groups_only', $this->json()->null()->defaultValue(null));
    }

    public function safeDown(): void
    {
        $this->alterColumn(self::TABLE, 'groups_only', $this->text()->null()->defaultValue(null));
        $this->alterColumn(self::TABLE, 'description', $this->text()->null()->defaultValue(null));
    }
}
