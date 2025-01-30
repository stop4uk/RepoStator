<?php

namespace app\modules\users\components\rbac\items;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\components\rbac\items
 */
final class Permissions
{
    const ADMIN_GROUP = 'admin_group';
    const ADMIN_GROUPTYPE = 'admin_groupType';
    const ADMIN_INCLUDES = 'admin_includes';
    const ADMIN_LOG = 'admin_log';
    const ADMIN_QUEUE = 'admin_queue';
    const ADMIN_QUEUE_SYSTEM = 'admin_queue_system';
    const ADMIN_QUEUE_TEMPLATE_LIST = 'admin_queue_template_list';
    const ADMIN_QUEUE_TEMPLATE_LIST_ALL = 'admin_queue_template_list_all';
    const ADMIN_QUEUE_TEMPLATE_LIST_GROUP = 'admin_queue_template_list_group';
    const ADMIN_SETTING = 'admin_setting';
    const ADMIN_USER_CREATE = 'admin_user_create';
    const ADMIN_USER_DELETE_ALL = 'admin_user_delete_all';
    const ADMIN_USER_DELETE_GROUP = 'admin_user_delete_group';
    const ADMIN_USER_EDIT_ALL = 'admin_user_edit_all';
    const ADMIN_USER_EDIT_GROUP = 'admin_user_edit_group';
    const ADMIN_USER_ENABLE_ALL = 'admin_user_enable_all';
    const ADMIN_USER_ENABLE_GROUP = 'admin_user_enable_group';
    const ADMIN_USER_LIST = 'admin_user_list';
    const ADMIN_USER_LIST_ALL = 'admin_user_list_all';
    const ADMIN_USER_LIST_GROUP = 'admin_user_list_group';
    const ADMIN_USER_VIEW_ALL = 'admin_user_view_all';
    const ADMIN_USER_VIEW_DELETE_ALL = 'admin_user_view_delete_all';
    const ADMIN_USER_VIEW_DELETE_GROUP = 'admin_user_view_delete_group';
    const ADMIN_USER_VIEW_GROUP = 'admin_user_view_group';
    const CONSTANT_CREATE = 'constant_create';
    const CONSTANT_DELETE_ALL = 'constant_delete_all';
    const CONSTANT_DELETE_GROUP = 'constant_delete_group';
    const CONSTANT_DELETE_MAIN = 'constant_delete_main';
    const CONSTANT_EDIT_ALL = 'constant_edit_all';
    const CONSTANT_EDIT_GROUP = 'constant_edit_group';
    const CONSTANT_EDIT_MAIN = 'constant_edit_main';
    const CONSTANT_ENABLE_ALL = 'constant_enable_all';
    const CONSTANT_ENABLE_GROUP = 'constant_enable_group';
    const CONSTANT_ENABLE_MAIN = 'constant_enable_main';
    const CONSTANT_LIST = 'constant_list';
    const CONSTANT_LIST_ALL = 'constant_list_all';
    const CONSTANT_LIST_GROUP = 'constant_list_group';
    const CONSTANT_LIST_MAIN = 'constant_list_main';
    const CONSTANT_VIEW_ALL = 'constant_view_all';
    const CONSTANT_VIEW_DELETE_ALL = 'constant_view_delete_all';
    const CONSTANT_VIEW_DELETE_GROUP = 'constant_view_delete_group';
    const CONSTANT_VIEW_DELETE_MAIN = 'constant_view_delete_main';
    const CONSTANT_VIEW_GROUP = 'constant_view_group';
    const CONSTANT_VIEW_MAIN = 'constant_view_main';
    const CONSTANTRULE_CREATE = 'constantRule_create';
    const CONSTANTRULE_DELETE_ALL = 'constantRule_delete_all';
    const CONSTANTRULE_DELETE_GROUP = 'constantRule_delete_group';
    const CONSTANTRULE_DELETE_MAIN = 'constantRule_delete_main';
    const CONSTANTRULE_EDIT_ALL = 'constantRule_edit_all';
    const CONSTANTRULE_EDIT_GROUP = 'constantRule_edit_group';
    const CONSTANTRULE_EDIT_MAIN = 'constantRule_edit_main';
    const CONSTANTRULE_ENABLE_ALL = 'constantRule_enable_all';
    const CONSTANTRULE_ENABLE_GROUP = 'constantRule_enable_group';
    const CONSTANTRULE_ENABLE_MAIN = 'constantRule_enable_main';
    const CONSTANTRULE_LIST = 'constantRule_list';
    const CONSTANTRULE_LIST_ALL = 'constantRule_list_all';
    const CONSTANTRULE_LIST_GROUP = 'constantRule_list_group';
    const CONSTANTRULE_LIST_MAIN = 'constantRule_list_main';
    const CONSTANTRULE_VIEW_ALL = 'constantRule_view_all';
    const CONSTANTRULE_VIEW_DELETE_ALL = 'constantRule_view_delete_all';
    const CONSTANTRULE_VIEW_DELETE_GROUP = 'constantRule_view_delete_group';
    const CONSTANTRULE_VIEW_DELETE_MAIN = 'constantRule_view_delete_main';
    const CONSTANTRULE_VIEW_GROUP = 'constantRule_view_group';
    const CONSTANTRULE_VIEW_MAIN = 'constantRule_view_main';
    const DATA_CHANGE_ALL = 'data_change_all';
    const DATA_CHANGE_GROUP = 'data_change_group';
    const DATA_CHANGE_MAIN = 'data_change_main';
    const DATA_CHECKFULL = 'data_checkFull';
    const DATA_CREATEFOR = 'data_createFor';
    const DATA_DELETE_ALL = 'data_delete_all';
    const DATA_DELETE_GROUP = 'data_delete_group';
    const DATA_DELETE_MAIN = 'data_delete_main';
    const DATA_EDIT_ALL = 'data_edit_all';
    const DATA_EDIT_GROUP = 'data_edit_group';
    const DATA_EDIT_MAIN = 'data_edit_main';
    const DATA_ENABLE_ALL = 'data_enable_all';
    const DATA_ENABLE_GROUP = 'data_enable_group';
    const DATA_ENABLE_MAIN = 'data_enable_main';
    const DATA_LIST = 'data_list';
    const DATA_LIST_ALL = 'data_list_all';
    const DATA_LIST_GROUP = 'data_list_group';
    const DATA_LIST_MAIN = 'data_list_main';
    const DATA_SEND = 'data_send';
    const DATA_SEND_ALL = 'data_send_all';
    const DATA_VIEW_ALL = 'data_view_all';
    const DATA_VIEW_DELETE_ALL = 'data_view_delete_all';
    const DATA_VIEW_DELETE_GROUP = 'data_view_delete_group';
    const DATA_VIEW_DELETE_MAIN = 'data_view_delete_main';
    const DATA_VIEW_GROUP = 'data_view_group';
    const DATA_VIEW_MAIN = 'data_view_main';
    const REPORT_CREATE = 'report_create';
    const REPORT_DELETE_ALL = 'report_delete_all';
    const REPORT_DELETE_GROUP = 'report_delete_group';
    const REPORT_DELETE_MAIN = 'report_delete_main';
    const REPORT_EDIT_ALL = 'report_edit_all';
    const REPORT_EDIT_GROUP = 'report_edit_group';
    const REPORT_EDIT_MAIN = 'report_edit_main';
    const REPORT_ENABLE_ALL = 'report_enable_all';
    const REPORT_ENABLE_GROUP = 'report_enable_group';
    const REPORT_ENABLE_MAIN = 'report_enable_main';
    const REPORT_INCLUDES = 'report_includes';
    const REPORT_LIST = 'report_list';
    const REPORT_LIST_ALL = 'report_list_all';
    const REPORT_LIST_GROUP = 'report_list_group';
    const REPORT_LIST_MAIN = 'report_list_main';
    const REPORT_VIEW_ALL = 'report_view_all';
    const REPORT_VIEW_DELETE_ALL = 'report_view_delete_all';
    const REPORT_VIEW_DELETE_GROUP = 'report_view_delete_group';
    const REPORT_VIEW_DELETE_MAIN = 'report_view_delete_main';
    const REPORT_VIEW_GROUP = 'report_view_group';
    const REPORT_VIEW_MAIN = 'report_view_main';
    const STATISTIC = 'statistic';
    const STRUCTURE_CREATE = 'structure_create';
    const STRUCTURE_DELETE_ALL = 'structure_delete_all';
    const STRUCTURE_DELETE_GROUP = 'structure_delete_group';
    const STRUCTURE_DELETE_MAIN = 'structure_delete_main';
    const STRUCTURE_EDIT_ALL = 'structure_edit_all';
    const STRUCTURE_EDIT_GROUP = 'structure_edit_group';
    const STRUCTURE_EDIT_MAIN = 'structure_edit_main';
    const STRUCTURE_ENABLE_ALL = 'structure_enable_all';
    const STRUCTURE_ENABLE_GROUP = 'structure_enable_group';
    const STRUCTURE_ENABLE_MAIN = 'structure_enable_main';
    const STRUCTURE_LIST = 'structure_list';
    const STRUCTURE_LIST_ALL = 'structure_list_all';
    const STRUCTURE_LIST_GROUP = 'structure_list_group';
    const STRUCTURE_LIST_MAIN = 'structure_list_main';
    const STRUCTURE_VIEW_ALL = 'structure_view_all';
    const STRUCTURE_VIEW_DELETE_ALL = 'structure_view_delete_all';
    const STRUCTURE_VIEW_DELETE_GROUP = 'structure_view_delete_group';
    const STRUCTURE_VIEW_DELETE_MAIN = 'structure_view_delete_main';
    const STRUCTURE_VIEW_GROUP = 'structure_view_group';
    const STRUCTURE_VIEW_MAIN = 'structure_view_main';
    const TEMPLATE_CREATE = 'template_create';
    const TEMPLATE_DELETE_ALL = 'template_delete_all';
    const TEMPLATE_DELETE_GROUP = 'template_delete_group';
    const TEMPLATE_DELETE_MAIN = 'template_delete_main';
    const TEMPLATE_EDIT_ALL = 'template_edit_all';
    const TEMPLATE_EDIT_GROUP = 'template_edit_group';
    const TEMPLATE_EDIT_MAIN = 'template_edit_main';
    const TEMPLATE_ENABLE_ALL = 'template_enable_all';
    const TEMPLATE_ENABLE_GROUP = 'template_enable_group';
    const TEMPLATE_ENABLE_MAIN = 'template_enable_main';
    const TEMPLATE_LIST = 'template_list';
    const TEMPLATE_LIST_ALL = 'template_list_all';
    const TEMPLATE_LIST_GROUP = 'template_list_group';
    const TEMPLATE_LIST_MAIN = 'template_list_main';
    const TEMPLATE_VIEW_ALL = 'template_view_all';
    const TEMPLATE_VIEW_DELETE_ALL = 'template_view_delete_all';
    const TEMPLATE_VIEW_DELETE_GROUP = 'template_view_delete_group';
    const TEMPLATE_VIEW_DELETE_MAIN = 'template_view_delete_main';
    const TEMPLATE_VIEW_GROUP = 'template_view_group';
    const TEMPLATE_VIEW_MAIN = 'template_view_main';
}