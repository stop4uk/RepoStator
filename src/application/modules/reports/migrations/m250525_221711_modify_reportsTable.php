<?php

namespace app\modules\reports\migrations;

use yii\db\{
    Migration,
    Expression
};
use yii\helpers\Json;

use app\helpers\CommonHelper;
use app\modules\reports\entities\ReportEntity;

final class m250525_221711_modify_reportsTable extends Migration
{
    const TABLE = '{{%reports}}';

    public function safeUp(): void
    {
        ReportEntity::updateAll(['groups_only' => null], 'groups_only = ""');
        ReportEntity::updateAll(['groups_required' => null], 'groups_required = ""');
        ReportEntity::updateAll(['description' => null], 'description = ""');
        $groupsRequired= ReportEntity::findAll(['is not', 'groups_required', new Expression('null')]);
        if ($groupsRequired) {
            foreach ($groupsRequired as $groupR) {
                $list = CommonHelper::explodeField($groupR->groups_required);
                $groupR->groups_required = Json::encode($list);
                $groupR->save(false);
            }
        }

        $groupsOnly= ReportEntity::findAll(['is not', 'groups_only', new Expression('null')]);
        if ($groupsOnly) {
            foreach ($groupsOnly as $groupO) {
                $list = CommonHelper::explodeField($groupO->groups_only);
                $groupO->groups_only = Json::encode($list);
                $groupO->save(false);
            }
        }

        $this->alterColumn(self::TABLE, 'groups_only', $this->json()->null());
        $this->alterColumn(self::TABLE, 'groups_required', $this->json()->null());
        $this->alterColumn(self::TABLE, 'description', $this->json()->null());
    }

    public function safeDown(): void
    {
        $this->alterColumn(self::TABLE, 'description', $this->text()->null()->defaultValue(null));
        $this->alterColumn(self::TABLE, 'groups_required', $this->text()->null()->defaultValue(null));
        $this->alterColumn(self::TABLE, 'groups_only', $this->text()->null()->defaultValue(null));
    }
}
