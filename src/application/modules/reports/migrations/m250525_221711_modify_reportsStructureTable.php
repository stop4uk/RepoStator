<?php

namespace app\modules\reports\migrations;

use yii\db\{
    Migration,
    Expression
};
use yii\helpers\Json;

use app\helpers\CommonHelper;
use app\modules\reports\entities\ReportStructureEntity;

final class m250525_221711_modify_reportsStructureTable extends Migration
{
    const TABLE = '{{%reports_structures}}';

    public function safeUp(): void
    {
        ReportStructureEntity::updateAll(['groups_only' => null], 'groups_only = ""');
        ReportStructureEntity::updateAll(['content' => null], 'content = ""');
        $groups= ReportStructureEntity::findAll(['is not', 'groups_only', new Expression('null')]);
        if ($groups) {
            foreach ($groups as $group) {
                $list = CommonHelper::explodeField($group->groups_only);
                $group->groups_only = Json::encode($list);
                $group->save(false);
            }
        }

        $this->alterColumn(self::TABLE, 'groups_only', $this->json()->null());
        $this->alterColumn(self::TABLE, 'content', $this->json()->notNull());
    }

    public function safeDown(): void
    {
        $this->alterColumn(self::TABLE, 'groups_only', $this->text()->null()->defaultValue(null));
        $this->alterColumn(self::TABLE, 'content', $this->text()->notNull());
    }
}
