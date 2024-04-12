<?php

use yii\db\Migration;

class m240412_140314_rights_item_childDataInsert extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $this->batchInsert('{{%rights_item_child}}',
                           ["parent", "child"],
                            [
    [
        'parent' => '',
        'child' => '',
    ],
    [
        'parent' => 'role_adminGroup',
        'child' => 'admin.group',
    ],
    [
        'parent' => 'role_adminGroupType',
        'child' => 'admin.groupType',
    ],
    [
        'parent' => 'role_adminGroup',
        'child' => 'admin.includes',
    ],
    [
        'parent' => 'role_adminGroupType',
        'child' => 'admin.includes',
    ],
    [
        'parent' => 'role_adminLog',
        'child' => 'admin.includes',
    ],
    [
        'parent' => 'role_adminQueueSystem',
        'child' => 'admin.includes',
    ],
    [
        'parent' => 'role_adminQueueTempalteAll',
        'child' => 'admin.includes',
    ],
    [
        'parent' => 'role_adminQueueTemplateGroup',
        'child' => 'admin.includes',
    ],
    [
        'parent' => 'role_adminUserAll',
        'child' => 'admin.includes',
    ],
    [
        'parent' => 'role_adminUserGroup',
        'child' => 'admin.includes',
    ],
    [
        'parent' => 'role_adminLog',
        'child' => 'admin.log',
    ],
    [
        'parent' => 'role_adminQueueSystem',
        'child' => 'admin.queue',
    ],
    [
        'parent' => 'role_adminQueueTempalteAll',
        'child' => 'admin.queue',
    ],
    [
        'parent' => 'role_adminQueueTemplateGroup',
        'child' => 'admin.queue',
    ],
    [
        'parent' => 'role_adminQueueSystem',
        'child' => 'admin.queue.system',
    ],
    [
        'parent' => 'role_adminQueueTempalteAll',
        'child' => 'admin.queue.template.list',
    ],
    [
        'parent' => 'role_adminQueueTemplateGroup',
        'child' => 'admin.queue.template.list',
    ],
    [
        'parent' => 'role_adminQueueTemplateAll',
        'child' => 'admin.queue.template.list.all',
    ],
    [
        'parent' => 'role_adminQueueTemplateGroup',
        'child' => 'admin.queue.template.list.group',
    ],
    [
        'parent' => 'role_adminUserAdd',
        'child' => 'admin.user.create',
    ],
    [
        'parent' => 'role_adminUserAll',
        'child' => 'admin.user.delete.all',
    ],
    [
        'parent' => 'role_adminUserGroup',
        'child' => 'admin.user.delete.group',
    ],
    [
        'parent' => 'role_adminUserAll',
        'child' => 'admin.user.edit.all',
    ],
    [
        'parent' => 'role_adminUserGroup',
        'child' => 'admin.user.edit.group',
    ],
    [
        'parent' => 'role_adminUserDeletedAll',
        'child' => 'admin.user.enable.all',
    ],
    [
        'parent' => 'role_adminUserDeletedGroup',
        'child' => 'admin.user.enable.group',
    ],
    [
        'parent' => 'admin.user.view.delete.group',
        'child' => 'admin.user.list',
    ],
    [
        'parent' => 'admin.user.view.group',
        'child' => 'admin.user.list',
    ],
    [
        'parent' => 'role_adminUserAll',
        'child' => 'admin.user.list',
    ],
    [
        'parent' => 'role_adminUserGroup',
        'child' => 'admin.user.list',
    ],
    [
        'parent' => 'admin.user.view.all',
        'child' => 'admin.user.list.all',
    ],
    [
        'parent' => 'role_adminUserAll',
        'child' => 'admin.user.list.all',
    ],
    [
        'parent' => 'admin.user.delete.all',
        'child' => 'admin.user.view.all',
    ],
    [
        'parent' => 'admin.user.edit.all',
        'child' => 'admin.user.view.all',
    ],
    [
        'parent' => 'role_adminUserAll',
        'child' => 'admin.user.view.all',
    ],
    [
        'parent' => 'admin.user.enable.all',
        'child' => 'admin.user.view.delete.all',
    ],
    [
        'parent' => 'role_adminUserDeletedAll',
        'child' => 'admin.user.view.delete.all',
    ],
    [
        'parent' => 'admin.user.enable.group',
        'child' => 'admin.user.view.delete.group',
    ],
    [
        'parent' => 'role_adminUserDeletedGroup',
        'child' => 'admin.user.view.delete.group',
    ],
    [
        'parent' => 'admin.user.delete.group',
        'child' => 'admin.user.view.group',
    ],
    [
        'parent' => 'admin.user.edit.group',
        'child' => 'admin.user.view.group',
    ],
    [
        'parent' => 'role_adminUserGroup',
        'child' => 'admin.user.view.group',
    ],
    [
        'parent' => 'admin.user.view.delete.all',
        'child' => 'admin.view.list.all',
    ],
    [
        'parent' => 'role_constantAdd',
        'child' => 'constant.create',
    ],
    [
        'parent' => 'role_constantAll',
        'child' => 'constant.delete.all',
    ],
    [
        'parent' => 'role_constantGroup',
        'child' => 'constant.delete.group',
    ],
    [
        'parent' => 'role_constantMain',
        'child' => 'constant.delete.main',
    ],
    [
        'parent' => 'role_constantAll',
        'child' => 'constant.edit.all',
    ],
    [
        'parent' => 'role_constantGroup',
        'child' => 'constant.edit.group',
    ],
    [
        'parent' => 'role_constantMain',
        'child' => 'constant.edit.main',
    ],
    [
        'parent' => 'role_constantDeletedAll',
        'child' => 'constant.enable.all',
    ],
    [
        'parent' => 'role_constantDeletedGroup',
        'child' => 'constant.enable.group',
    ],
    [
        'parent' => 'role_constantDeletedMain',
        'child' => 'constant.enable.main',
    ],
    [
        'parent' => 'role_constantAll',
        'child' => 'constant.list',
    ],
    [
        'parent' => 'role_constantGroup',
        'child' => 'constant.list',
    ],
    [
        'parent' => 'role_constantMain',
        'child' => 'constant.list',
    ],
    [
        'parent' => 'constant.view.all',
        'child' => 'constant.list.all',
    ],
    [
        'parent' => 'constant.view.delete.all',
        'child' => 'constant.list.all',
    ],
    [
        'parent' => 'role_constantAll',
        'child' => 'constant.list.all',
    ],
    [
        'parent' => 'constant.list.all',
        'child' => 'constant.list.group',
    ],
    [
        'parent' => 'constant.view.delete.group',
        'child' => 'constant.list.group',
    ],
    [
        'parent' => 'constant.view.group',
        'child' => 'constant.list.group',
    ],
    [
        'parent' => 'role_constantGroup',
        'child' => 'constant.list.group',
    ],
    [
        'parent' => 'constant.list.group',
        'child' => 'constant.list.main',
    ],
    [
        'parent' => 'constant.view.delete.main',
        'child' => 'constant.list.main',
    ],
    [
        'parent' => 'constant.view.main',
        'child' => 'constant.list.main',
    ],
    [
        'parent' => 'role_constantMain',
        'child' => 'constant.list.main',
    ],
    [
        'parent' => 'role_constantGroup',
        'child' => 'constant.send',
    ],
    [
        'parent' => 'role_constantMain',
        'child' => 'constant.send',
    ],
    [
        'parent' => 'constant.delete.all',
        'child' => 'constant.view.all',
    ],
    [
        'parent' => 'constant.edit.all',
        'child' => 'constant.view.all',
    ],
    [
        'parent' => 'role_constantAll',
        'child' => 'constant.view.all',
    ],
    [
        'parent' => 'constant.enable.all',
        'child' => 'constant.view.delete.all',
    ],
    [
        'parent' => 'constant.enable.group',
        'child' => 'constant.view.delete.group',
    ],
    [
        'parent' => 'constant.enable.main',
        'child' => 'constant.view.delete.main',
    ],
    [
        'parent' => 'constant.delete.group',
        'child' => 'constant.view.group',
    ],
    [
        'parent' => 'constant.edit.group',
        'child' => 'constant.view.group',
    ],
    [
        'parent' => 'role_constantGroup',
        'child' => 'constant.view.group',
    ],
    [
        'parent' => 'constant.delete.main',
        'child' => 'constant.view.main',
    ],
    [
        'parent' => 'constant.edit.main',
        'child' => 'constant.view.main',
    ],
    [
        'parent' => 'role_constantMain',
        'child' => 'constant.view.main',
    ],
    [
        'parent' => 'role_constantRuleAdd',
        'child' => 'constantRule.create',
    ],
    [
        'parent' => 'role_constantRuleAll',
        'child' => 'constantRule.delete.all',
    ],
    [
        'parent' => 'role_constantRuleGroup',
        'child' => 'constantRule.delete.group',
    ],
    [
        'parent' => 'role_constantRuleMain',
        'child' => 'constantRule.delete.main',
    ],
    [
        'parent' => 'role_constantRuleAll',
        'child' => 'constantRule.edit.all',
    ],
    [
        'parent' => 'role_constantRuleGroup',
        'child' => 'constantRule.edit.group',
    ],
    [
        'parent' => 'role_constantRuleMain',
        'child' => 'constantRule.edit.main',
    ],
    [
        'parent' => 'role_constantRuleDeletedAll',
        'child' => 'constantRule.enable.all',
    ],
    [
        'parent' => 'role_constantRuleDeletedGroup',
        'child' => 'constantRule.enable.group',
    ],
    [
        'parent' => 'role_constantRuleDeletedMain',
        'child' => 'constantRule.enable.main',
    ],
    [
        'parent' => 'constantRule.list.main',
        'child' => 'constantRule.list',
    ],
    [
        'parent' => 'role_constantRuleAll',
        'child' => 'constantRule.list',
    ],
    [
        'parent' => 'role_constantRuleGroup',
        'child' => 'constantRule.list',
    ],
    [
        'parent' => 'constantRule.view.all',
        'child' => 'constantRule.list.all',
    ],
    [
        'parent' => 'constantRule.view.delete.all',
        'child' => 'constantRule.list.all',
    ],
    [
        'parent' => 'role_constantRuleAll',
        'child' => 'constantRule.list.all',
    ],
    [
        'parent' => 'constantRule.list.all',
        'child' => 'constantRule.list.group',
    ],
    [
        'parent' => 'constantRule.view.delete.group',
        'child' => 'constantRule.list.group',
    ],
    [
        'parent' => 'constantRule.view.group',
        'child' => 'constantRule.list.group',
    ],
    [
        'parent' => 'role_constantRuleGroup',
        'child' => 'constantRule.list.group',
    ],
    [
        'parent' => 'constantRule.list.group',
        'child' => 'constantRule.list.main',
    ],
    [
        'parent' => 'constantRule.view.delete.main',
        'child' => 'constantRule.list.main',
    ],
    [
        'parent' => 'constantRule.view.main',
        'child' => 'constantRule.list.main',
    ],
    [
        'parent' => 'role_constantRuleMain',
        'child' => 'constantRule.list.main',
    ],
    [
        'parent' => 'role_constantRuleGroup',
        'child' => 'constantRule.send',
    ],
    [
        'parent' => 'role_constantRuleMain',
        'child' => 'constantRule.send',
    ],
    [
        'parent' => 'constantRule.delete.all',
        'child' => 'constantRule.view.all',
    ],
    [
        'parent' => 'constantRule.edit.all',
        'child' => 'constantRule.view.all',
    ],
    [
        'parent' => 'role_constantRuleAll',
        'child' => 'constantRule.view.all',
    ],
    [
        'parent' => 'constantRule.enable.all',
        'child' => 'constantRule.view.delete.all',
    ],
    [
        'parent' => 'constantRule.enable.group',
        'child' => 'constantRule.view.delete.group',
    ],
    [
        'parent' => 'constantRule.enable.main',
        'child' => 'constantRule.view.delete.main',
    ],
    [
        'parent' => 'constantRule.delete.group',
        'child' => 'constantRule.view.group',
    ],
    [
        'parent' => 'constantRule.edit.group',
        'child' => 'constantRule.view.group',
    ],
    [
        'parent' => 'role_constantRuleGroup',
        'child' => 'constantRule.view.group',
    ],
    [
        'parent' => 'constantRule.delete.main',
        'child' => 'constantRule.view.main',
    ],
    [
        'parent' => 'constantRule.edit.main',
        'child' => 'constantRule.view.main',
    ],
    [
        'parent' => 'role_constantRuleMain',
        'child' => 'constantRule.view.main',
    ],
    [
        'parent' => 'role_dataAll',
        'child' => 'data.change.all',
    ],
    [
        'parent' => 'role_dataGroup',
        'child' => 'data.change.group',
    ],
    [
        'parent' => 'role_dataMain',
        'child' => 'data.change.main',
    ],
    [
        'parent' => 'role_dataAll',
        'child' => 'data.delete.all',
    ],
    [
        'parent' => 'role_dataGroup',
        'child' => 'data.delete.group',
    ],
    [
        'parent' => 'role_dataMain',
        'child' => 'data.delete.main',
    ],
    [
        'parent' => 'role_dataAll',
        'child' => 'data.edit.all',
    ],
    [
        'parent' => 'role_dataGroup',
        'child' => 'data.edit.group',
    ],
    [
        'parent' => 'role_dataMain',
        'child' => 'data.edit.main',
    ],
    [
        'parent' => 'role_dataDeletedAll',
        'child' => 'data.enable.all',
    ],
    [
        'parent' => 'role_dataDeletedGroup',
        'child' => 'data.enable.group',
    ],
    [
        'parent' => 'role_dataDeletedMain',
        'child' => 'data.enable.main',
    ],
    [
        'parent' => 'role_dataAll',
        'child' => 'data.list',
    ],
    [
        'parent' => 'role_dataGroup',
        'child' => 'data.list',
    ],
    [
        'parent' => 'role_dataMain',
        'child' => 'data.list',
    ],
    [
        'parent' => 'data.view.all',
        'child' => 'data.list.all',
    ],
    [
        'parent' => 'data.view.delete.all',
        'child' => 'data.list.all',
    ],
    [
        'parent' => 'role_dataAll',
        'child' => 'data.list.all',
    ],
    [
        'parent' => 'data.list.all',
        'child' => 'data.list.group',
    ],
    [
        'parent' => 'data.view.delete.group',
        'child' => 'data.list.group',
    ],
    [
        'parent' => 'data.view.group',
        'child' => 'data.list.group',
    ],
    [
        'parent' => 'role_dataGroup',
        'child' => 'data.list.group',
    ],
    [
        'parent' => 'data.list.group',
        'child' => 'data.list.main',
    ],
    [
        'parent' => 'data.view.delete.main',
        'child' => 'data.list.main',
    ],
    [
        'parent' => 'data.view.main',
        'child' => 'data.list.main',
    ],
    [
        'parent' => 'role_dataMain',
        'child' => 'data.list.main',
    ],
    [
        'parent' => 'role_dataAll',
        'child' => 'data.send',
    ],
    [
        'parent' => 'role_dataGroup',
        'child' => 'data.send',
    ],
    [
        'parent' => 'role_dataMain',
        'child' => 'data.send',
    ],
    [
        'parent' => 'data.delete.all',
        'child' => 'data.view.all',
    ],
    [
        'parent' => 'data.edit.all',
        'child' => 'data.view.all',
    ],
    [
        'parent' => 'role_dataAll',
        'child' => 'data.view.all',
    ],
    [
        'parent' => 'data.enable.all',
        'child' => 'data.view.delete.all',
    ],
    [
        'parent' => 'data.enable.group',
        'child' => 'data.view.delete.group',
    ],
    [
        'parent' => 'data.enable.main',
        'child' => 'data.view.delete.main',
    ],
    [
        'parent' => 'data.delete.group',
        'child' => 'data.view.group',
    ],
    [
        'parent' => 'data.edit.group',
        'child' => 'data.view.group',
    ],
    [
        'parent' => 'role_dataGroup',
        'child' => 'data.view.group',
    ],
    [
        'parent' => 'data.delete.main',
        'child' => 'data.view.main',
    ],
    [
        'parent' => 'data.edit.main',
        'child' => 'data.view.main',
    ],
    [
        'parent' => 'role_dataMain',
        'child' => 'data.view.main',
    ],
    [
        'parent' => 'role_reportAdd',
        'child' => 'report.create',
    ],
    [
        'parent' => 'role_reportAll',
        'child' => 'report.delete.all',
    ],
    [
        'parent' => 'role_reportGroup',
        'child' => 'report.delete.group',
    ],
    [
        'parent' => 'role_reportMain',
        'child' => 'report.delete.main',
    ],
    [
        'parent' => 'role_reportAll',
        'child' => 'report.edit.all',
    ],
    [
        'parent' => 'role_reportGroup',
        'child' => 'report.edit.group',
    ],
    [
        'parent' => 'role_reportMain',
        'child' => 'report.edit.main',
    ],
    [
        'parent' => 'role_reportDeletedAll',
        'child' => 'report.enable.all',
    ],
    [
        'parent' => 'role_reportDeletedGroup',
        'child' => 'report.enable.group',
    ],
    [
        'parent' => 'role_reportDeletedMain',
        'child' => 'report.enable.main',
    ],
    [
        'parent' => 'role_constantAdd',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantDeletedAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantDeletedGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantDeletedMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantRuleAdd',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantRuleAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantRuleDeletedAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantRuleDeletedGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantRuleDeletedMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantRuleGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_constantRuleMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_reportAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_reportDeletedAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_reportDeletedGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_reportDeletedMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_reportGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_reportMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_structureAdd',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_structureAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_structureDeletedAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_structureDeletedGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_structureDeletedMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_structureGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_structureMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_templateAdd',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_templateAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_templateDeletedAll',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_templateDeletedGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_templateDeletedMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_templateGroup',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_templateMain',
        'child' => 'report.includes',
    ],
    [
        'parent' => 'role_reportAll',
        'child' => 'report.list',
    ],
    [
        'parent' => 'role_reportGroup',
        'child' => 'report.list',
    ],
    [
        'parent' => 'role_reportAll',
        'child' => 'report.list.all',
    ],
    [
        'parent' => 'role_reportGroup',
        'child' => 'report.list.group',
    ],
    [
        'parent' => 'role_reportMain',
        'child' => 'report.list.main',
    ],
    [
        'parent' => 'role_reportGroup',
        'child' => 'report.send',
    ],
    [
        'parent' => 'role_reportMain',
        'child' => 'report.send',
    ],
    [
        'parent' => 'role_reportAll',
        'child' => 'report.view.all',
    ],
    [
        'parent' => 'role_reportGroup',
        'child' => 'report.view.group',
    ],
    [
        'parent' => 'role_reportMain',
        'child' => 'report.view.main',
    ],
    [
        'parent' => 'role_structureAdd',
        'child' => 'structure.create',
    ],
    [
        'parent' => 'role_structureAll',
        'child' => 'structure.delete.all',
    ],
    [
        'parent' => 'role_structureGroup',
        'child' => 'structure.delete.group',
    ],
    [
        'parent' => 'role_structureMain',
        'child' => 'structure.delete.main',
    ],
    [
        'parent' => 'role_structureAll',
        'child' => 'structure.edit.all',
    ],
    [
        'parent' => 'role_structureGroup',
        'child' => 'structure.edit.group',
    ],
    [
        'parent' => 'role_structureMain',
        'child' => 'structure.edit.main',
    ],
    [
        'parent' => 'role_structureDeletedAll',
        'child' => 'structure.enable.all',
    ],
    [
        'parent' => 'role_structureDeletedGroup',
        'child' => 'structure.enable.group',
    ],
    [
        'parent' => 'role_structureDeletedMain',
        'child' => 'structure.enable.main',
    ],
    [
        'parent' => 'role_structureAll',
        'child' => 'structure.list',
    ],
    [
        'parent' => 'role_structureGroup',
        'child' => 'structure.list',
    ],
    [
        'parent' => 'structure.list.main',
        'child' => 'structure.list',
    ],
    [
        'parent' => 'role_structureAll',
        'child' => 'structure.list.all',
    ],
    [
        'parent' => 'structure.view.all',
        'child' => 'structure.list.all',
    ],
    [
        'parent' => 'structure.view.delete.all',
        'child' => 'structure.list.all',
    ],
    [
        'parent' => 'role_structureGroup',
        'child' => 'structure.list.group',
    ],
    [
        'parent' => 'structure.list.all',
        'child' => 'structure.list.group',
    ],
    [
        'parent' => 'structure.view.delete.group',
        'child' => 'structure.list.group',
    ],
    [
        'parent' => 'structure.view.group',
        'child' => 'structure.list.group',
    ],
    [
        'parent' => 'role_structureMain',
        'child' => 'structure.list.main',
    ],
    [
        'parent' => 'structure.list.group',
        'child' => 'structure.list.main',
    ],
    [
        'parent' => 'structure.view.delete.main',
        'child' => 'structure.list.main',
    ],
    [
        'parent' => 'structure.view.main',
        'child' => 'structure.list.main',
    ],
    [
        'parent' => 'role_structureGroup',
        'child' => 'structure.send',
    ],
    [
        'parent' => 'role_structureMain',
        'child' => 'structure.send',
    ],
    [
        'parent' => 'role_structureAll',
        'child' => 'structure.view.all',
    ],
    [
        'parent' => 'structure.delete.all',
        'child' => 'structure.view.all',
    ],
    [
        'parent' => 'structure.edit.all',
        'child' => 'structure.view.all',
    ],
    [
        'parent' => 'structure.enable.all',
        'child' => 'structure.view.delete.all',
    ],
    [
        'parent' => 'structure.enable.group',
        'child' => 'structure.view.delete.group',
    ],
    [
        'parent' => 'structure.enable.main',
        'child' => 'structure.view.delete.main',
    ],
    [
        'parent' => 'role_structureGroup',
        'child' => 'structure.view.group',
    ],
    [
        'parent' => 'structure.delete.group',
        'child' => 'structure.view.group',
    ],
    [
        'parent' => 'structure.edit.group',
        'child' => 'structure.view.group',
    ],
    [
        'parent' => 'role_structureMain',
        'child' => 'structure.view.main',
    ],
    [
        'parent' => 'structure.delete.main',
        'child' => 'structure.view.main',
    ],
    [
        'parent' => 'structure.edit.main',
        'child' => 'structure.view.main',
    ],
    [
        'parent' => 'role_templateAdd',
        'child' => 'template.create',
    ],
    [
        'parent' => 'role_templateAll',
        'child' => 'template.delete.all',
    ],
    [
        'parent' => 'role_templateGroup',
        'child' => 'template.delete.group',
    ],
    [
        'parent' => 'role_templateMain',
        'child' => 'template.delete.main',
    ],
    [
        'parent' => 'role_templateAll',
        'child' => 'template.edit.all',
    ],
    [
        'parent' => 'role_templateGroup',
        'child' => 'template.edit.group',
    ],
    [
        'parent' => 'role_templateMain',
        'child' => 'template.edit.main',
    ],
    [
        'parent' => 'role_templateDeletedAll',
        'child' => 'template.enable.all',
    ],
    [
        'parent' => 'role_templateDeletedGroup',
        'child' => 'template.enable.group',
    ],
    [
        'parent' => 'role_templateDeletedMain',
        'child' => 'template.enable.main',
    ],
    [
        'parent' => 'role_templateAll',
        'child' => 'template.list',
    ],
    [
        'parent' => 'role_templateGroup',
        'child' => 'template.list',
    ],
    [
        'parent' => 'template.list.main',
        'child' => 'template.list',
    ],
    [
        'parent' => 'role_templateAll',
        'child' => 'template.list.all',
    ],
    [
        'parent' => 'template.view.all',
        'child' => 'template.list.all',
    ],
    [
        'parent' => 'template.view.delete.all',
        'child' => 'template.list.all',
    ],
    [
        'parent' => 'role_templateGroup',
        'child' => 'template.list.group',
    ],
    [
        'parent' => 'template.list.all',
        'child' => 'template.list.group',
    ],
    [
        'parent' => 'template.view.delete.group',
        'child' => 'template.list.group',
    ],
    [
        'parent' => 'template.view.group',
        'child' => 'template.list.group',
    ],
    [
        'parent' => 'role_templateMain',
        'child' => 'template.list.main',
    ],
    [
        'parent' => 'template.list.group',
        'child' => 'template.list.main',
    ],
    [
        'parent' => 'template.view.delete.main',
        'child' => 'template.list.main',
    ],
    [
        'parent' => 'template.view.main',
        'child' => 'template.list.main',
    ],
    [
        'parent' => 'role_templateGroup',
        'child' => 'template.send',
    ],
    [
        'parent' => 'role_templateMain',
        'child' => 'template.send',
    ],
    [
        'parent' => 'role_templateAll',
        'child' => 'template.view.all',
    ],
    [
        'parent' => 'template.delete.all',
        'child' => 'template.view.all',
    ],
    [
        'parent' => 'template.edit.all',
        'child' => 'template.view.all',
    ],
    [
        'parent' => 'template.enable.all',
        'child' => 'template.view.delete.all',
    ],
    [
        'parent' => 'template.enable.group',
        'child' => 'template.view.delete.group',
    ],
    [
        'parent' => 'template.enable.main',
        'child' => 'template.view.delete.main',
    ],
    [
        'parent' => 'role_templateGroup',
        'child' => 'template.view.group',
    ],
    [
        'parent' => 'template.delete.group',
        'child' => 'template.view.group',
    ],
    [
        'parent' => 'template.edit.group',
        'child' => 'template.view.group',
    ],
    [
        'parent' => 'role_templateMain',
        'child' => 'template.view.main',
    ],
    [
        'parent' => 'template.delete.main',
        'child' => 'template.view.main',
    ],
    [
        'parent' => 'template.edit.main',
        'child' => 'template.view.main',
    ],
    [
        'parent' => 'role_dataCheckFull',
        'child' => 'data.checkFull',
    ],
    [
        'parent' => 'role_dataCreateFor',
        'child' => 'data.createFor',
    ],
]
        );
    }

    public function safeDown()
    {
        //$this->truncateTable('{{%rights_item_child}} CASCADE');
    }
}
