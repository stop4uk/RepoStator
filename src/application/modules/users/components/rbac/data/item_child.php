<?php

use app\modules\users\components\rbac\items\{
    Roles,
    Permissions
};

return [
    Roles::ADMIN => [
        'type' => 1,
        'description' => 'Администратор',
    ],
    Roles::ROLE_ADMINGROUP => [
        'type' => 1,
        'description' => 'Админка. Группы. Управление',
        'children' => [
            Permissions::ADMIN_GROUP,
            Permissions::ADMIN_INCLUDES,
        ]
    ],
    Roles::ROLE_ADMINGROUPTYPE => [
        'type' => 1,
        'description' => 'Админка. Типы групп. Управление',
        'children' => [
            Permissions::ADMIN_GROUPTYPE,
            Permissions::ADMIN_INCLUDES,
        ]
    ],
    Roles::ROLE_ADMINLOG => [
        'type' => 1,
        'description' => 'Админка. Логи',
        'children' => [
            Permissions::ADMIN_INCLUDES,
            Permissions::ADMIN_LOG,
        ]
    ],
    Roles::ROLE_ADMINQUEUESYSTEM => [
        'type' => 1,
        'description' => 'Админка. Очереди. Системная',
        'children' => [
            Permissions::ADMIN_INCLUDES,
            Permissions::ADMIN_QUEUE,
            Permissions::ADMIN_QUEUE_SYSTEM,
        ]
    ],
    Roles::ROLE_ADMINQUEUETEMPLATEALL => [
        'type' => 1,
        'description' => 'Админка. Очереди. Шаблоны. Своя и подчиненные группы',
        'children' => [
            Permissions::ADMIN_QUEUE_TEMPLATE_LIST_ALL,
        ]
    ],
    Roles::ROLE_ADMINQUEUETEMPLATEGROUP => [
        'type' => 1,
        'description' => 'Админка. Очереди. Шаблоны. Своя группа',
        'children' => [
            Permissions::ADMIN_INCLUDES,
            Permissions::ADMIN_QUEUE,
            Permissions::ADMIN_QUEUE_TEMPLATE_LIST,
            Permissions::ADMIN_QUEUE_TEMPLATE_LIST_GROUP,
        ]
    ],
    Roles::ROLE_ADMINUSERADD => [
        'type' => 1,
        'description' => 'Админка. Пользователи. Создание',
        'children' => [
            Permissions::ADMIN_USER_CREATE,
        ]
    ],
    Roles::ROLE_ADMINUSERALL => [
        'type' => 1,
        'description' => 'Админка. Пользователи. Своя и подчиненные группы',
        'children' => [
            Permissions::ADMIN_INCLUDES,
            Permissions::ADMIN_USER_DELETE_ALL,
            Permissions::ADMIN_USER_EDIT_ALL,
            Permissions::ADMIN_USER_LIST,
            Permissions::ADMIN_USER_LIST_ALL,
            Permissions::ADMIN_USER_VIEW_ALL,
        ]
    ],
    Roles::ROLE_ADMINUSERDELETEDALL => [
        'type' => 1,
        'description' => 'Админка. Пользователи. Удаленные. Своя и подчиненные группы',
        'children' => [
            Permissions::ADMIN_USER_ENABLE_ALL,
            Permissions::ADMIN_USER_VIEW_DELETE_ALL,
        ]
    ],
    Roles::ROLE_ADMINUSERDELETEDGROUP => [
        'type' => 1,
        'description' => 'Админка. Пользователи. Удаленные. Своя группа',
        'children' => [
            Permissions::ADMIN_USER_ENABLE_GROUP,
            Permissions::ADMIN_USER_VIEW_DELETE_GROUP,
        ]
    ],
    Roles::ROLE_ADMINUSERGROUP => [
        'type' => 1,
        'description' => 'Админка. Пользователи. Своя группа',
        'children' => [
            Permissions::ADMIN_INCLUDES,
            Permissions::ADMIN_USER_DELETE_GROUP,
            Permissions::ADMIN_USER_EDIT_GROUP,
            Permissions::ADMIN_USER_LIST,
            Permissions::ADMIN_USER_VIEW_GROUP,
        ]
    ],
    Roles::ROLE_CONSTANTADD => [
        'type' => 1,
        'description' => 'Константы. Создание',
        'children' => [
            Permissions::CONSTANT_CREATE,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTALL => [
        'type' => 1,
        'description' => 'Константы. Своя и подчиненные группы',
        'children' => [
            Permissions::CONSTANT_DELETE_ALL,
            Permissions::CONSTANT_EDIT_ALL,
            Permissions::CONSTANT_LIST,
            Permissions::CONSTANT_LIST_ALL,
            Permissions::CONSTANT_VIEW_ALL,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTDELETEDALL => [
        'type' => 1,
        'description' => 'Константы. Удаленные. Своия и подчиненные группы',
        'children' => [
            Permissions::CONSTANT_ENABLE_ALL,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTDELETEDGROUP => [
        'type' => 1,
        'description' => 'Константы. Удаленные. Своя группа',
        'children' => [
            Permissions::CONSTANT_ENABLE_GROUP,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTDELETEDMAIN => [
        'type' => 1,
        'description' => 'Константы. Удаленные. Только свои',
        'children' => [
            Permissions::CONSTANT_ENABLE_MAIN,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTGROUP => [
        'type' => 1,
        'description' => 'Константы. Только свои',
        'children' => [
            Permissions::CONSTANT_DELETE_GROUP,
            Permissions::CONSTANT_EDIT_GROUP,
            Permissions::CONSTANT_LIST,
            Permissions::CONSTANT_LIST_GROUP,
            Permissions::CONSTANT_VIEW_GROUP,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTMAIN => [
        'type' => 1,
        'description' => 'Константы. Только свои',
        'children' => [
            Permissions::CONSTANT_DELETE_MAIN,
            Permissions::CONSTANT_EDIT_MAIN,
            Permissions::CONSTANT_LIST,
            Permissions::CONSTANT_LIST_MAIN,
            Permissions::CONSTANT_VIEW_MAIN,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTRULEADD => [
        'type' => 1,
        'description' => 'Правила сложения. Создание',
        'children' => [
            Permissions::CONSTANTRULE_CREATE,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTRULEALL => [
        'type' => 1,
        'description' => 'Правила сложения. Своя и подчиненные группы',
        'children' => [
            Permissions::CONSTANTRULE_DELETE_ALL,
            Permissions::CONSTANTRULE_EDIT_ALL,
            Permissions::CONSTANTRULE_LIST,
            Permissions::CONSTANTRULE_LIST_ALL,
            Permissions::CONSTANTRULE_VIEW_ALL,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTRULEDELETEDALL => [
        'type' => 1,
        'description' => 'Правила сложения. Удаленные. Своя и подчиненные группы',
        'children' => [
            Permissions::CONSTANTRULE_ENABLE_ALL,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTRULEDELETEDGROUP => [
        'type' => 1,
        'description' => 'Правила сложения. Удаленные. Своя группа',
        'children' => [
            Permissions::CONSTANTRULE_ENABLE_GROUP,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTRULEDELETEDMAIN => [
        'type' => 1,
        'description' => 'Правила сложения. Удаленные. Только свои',
        'children' => [
            Permissions::CONSTANTRULE_ENABLE_MAIN,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTRULEGROUP => [
        'type' => 1,
        'description' => 'Правила сложения. Своя группа',
        'children' => [
            Permissions::CONSTANTRULE_DELETE_GROUP,
            Permissions::CONSTANTRULE_EDIT_GROUP,
            Permissions::CONSTANTRULE_LIST,
            Permissions::CONSTANTRULE_LIST_GROUP,
            Permissions::CONSTANTRULE_VIEW_GROUP,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_CONSTANTRULEMAIN => [
        'type' => 1,
        'description' => 'Правила сложения. Только свои',
        'children' => [
            Permissions::CONSTANTRULE_DELETE_MAIN,
            Permissions::CONSTANTRULE_EDIT_MAIN,
            Permissions::CONSTANTRULE_LIST_MAIN,
            Permissions::CONSTANTRULE_VIEW_MAIN,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_DATASEND => [
        'type' => 1,
        'description' => 'Сведения. Только передача',
        'children' => [
            Permissions::DATA_SEND
        ]
    ],
    Roles::ROLE_DATAALL => [
        'type' => 1,
        'description' => 'Сведения. Своя и подчиненные группы',
        'children' => [
            Permissions::DATA_CHANGE_ALL,
            Permissions::DATA_DELETE_ALL,
            Permissions::DATA_EDIT_ALL,
            Permissions::DATA_LIST,
            Permissions::DATA_LIST_ALL,
            Permissions::DATA_SEND,
            Permissions::DATA_VIEW_ALL,
        ]
    ],
    Roles::ROLE_DATACHECKFULL => [
        'type' => 1,
        'description' => 'Сведения. Проверка полноты передачи. Своя и подчиненные группы',
        'children' => [
            Permissions::DATA_CHECKFULL,
        ]
    ],
    Roles::ROLE_DATACREATEFOR => [
        'type' => 1,
        'description' => 'Сведения. Передача за группу. Своя и подчиненные группы',
        'children' => [
            Permissions::DATA_CREATEFOR,
        ]
    ],
    Roles::ROLE_DATADELETEDALL => [
        'type' => 1,
        'description' => 'Сведения. Удаленные. Своя и подчиенные группы',
        'children' => [
            Permissions::DATA_ENABLE_ALL,
        ]
    ],
    Roles::ROLE_DATADELETEDGROUP => [
        'type' => 1,
        'description' => 'Сведения. Удаленные. Своя группа',
        'children' => [
            Permissions::DATA_ENABLE_GROUP,
        ]
    ],
    Roles::ROLE_DATADELETEDMAIN => [
        'type' => 1,
        'description' => 'Сведения. Удаленные. Только свои',
        'children' => [
            Permissions::DATA_ENABLE_MAIN,
        ]
    ],
    Roles::ROLE_DATAGROUP => [
        'type' => 1,
        'description' => 'Сведения. Своя группа',
        'children' => [
            Permissions::DATA_CHANGE_GROUP,
            Permissions::DATA_DELETE_GROUP,
            Permissions::DATA_EDIT_GROUP,
            Permissions::DATA_LIST,
            Permissions::DATA_LIST_GROUP,
            Permissions::DATA_SEND,
            Permissions::DATA_VIEW_GROUP,
        ]
    ],
    Roles::ROLE_DATAMAIN => [
        'type' => 1,
        'description' => 'Сведения. Только свои',
        'children' => [
            Permissions::DATA_CHANGE_MAIN,
            Permissions::DATA_DELETE_MAIN,
            Permissions::DATA_EDIT_MAIN,
            Permissions::DATA_LIST,
            Permissions::DATA_LIST_MAIN,
            Permissions::DATA_SEND,
            Permissions::DATA_VIEW_MAIN,
        ]
    ],
    Roles::ROLE_REPORTADD => [
        'type' => 1,
        'description' => 'Отчеты. Создание',
        'children' => [
            Permissions::REPORT_CREATE,
        ]
    ],
    Roles::ROLE_REPORTALL => [
        'type' => 1,
        'description' => 'Отчеты. Своя и подчиненные группы',
        'children' => [
            Permissions::REPORT_DELETE_ALL,
            Permissions::REPORT_EDIT_ALL,
            Permissions::REPORT_INCLUDES,
            Permissions::REPORT_LIST,
            Permissions::REPORT_LIST_ALL,
            Permissions::REPORT_VIEW_ALL,
        ]
    ],
    Roles::ROLE_REPORTDELETEDALL => [
        'type' => 1,
        'description' => 'Отчеты. Своя и подчиненные группы',
        'children' => [
            Permissions::REPORT_ENABLE_ALL,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_REPORTDELETEDGROUP => [
        'type' => 1,
        'description' => 'Отчеты. Удаленные. Своя группа',
        'children' => [
            Permissions::REPORT_ENABLE_GROUP,
            Permissions::REPORT_INCLUDES,
        ]
    ],
    Roles::ROLE_REPORTDELETEMAIN => [
        'type' => 1,
        'description' => 'Отчеты. Удаленные. Только свои'],
    Roles::ROLE_REPORTGROUP => [
        'type' => 1,
        'description' => 'Отчеты. Своя группа',
        'children' => [
            Permissions::REPORT_DELETE_GROUP,
            Permissions::REPORT_EDIT_GROUP,
            Permissions::REPORT_INCLUDES,
            Permissions::REPORT_LIST,
            Permissions::REPORT_LIST_GROUP,
            Permissions::REPORT_VIEW_GROUP,
        ]
    ],
    Roles::ROLE_REPORTMAIN => [
        'type' => 1,
        'description' => 'Отчеты. Только свои',
        'children' => [
            Permissions::REPORT_DELETE_MAIN,
            Permissions::REPORT_EDIT_MAIN,
            Permissions::REPORT_INCLUDES,
            Permissions::REPORT_LIST_MAIN,
            Permissions::REPORT_VIEW_MAIN,
        ]
    ],
    Roles::ROLE_STRUCTUREADD => [
        'type' => 1,
        'description' => 'Структуры. Создание',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::STRUCTURE_CREATE,
        ]
    ],
    Roles::ROLE_STRUCTUREALL => [
        'type' => 1,
        'description' => 'Структуры. Своя и подчиненные группы',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::STRUCTURE_DELETE_ALL,
            Permissions::STRUCTURE_EDIT_ALL,
            Permissions::STRUCTURE_LIST,
            Permissions::STRUCTURE_LIST_ALL,
            Permissions::STRUCTURE_VIEW_ALL,
        ]
    ],
    Roles::ROLE_STRUCTUREDELETEDALL => [
        'type' => 1,
        'description' => 'Структуры. Удаленные. Своя и подчиненные группы',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::STRUCTURE_ENABLE_ALL,
        ]
    ],
    Roles::ROLE_STRUCTUREDELETEDGROUP => [
        'type' => 1,
        'description' => 'Структуры. Удаленные. Своя группа',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::STRUCTURE_ENABLE_GROUP,
        ]
    ],
    Roles::ROLE_STRUCTUREDELETEDMAIN => [
        'type' => 1,
        'description' => 'Структуры. Удаленные. Только свои',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::STRUCTURE_ENABLE_MAIN,
        ]
    ],
    Roles::ROLE_STRUCTUREGROUP => [
        'type' => 1,
        'description' => 'Структуры. Своя группа',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::STRUCTURE_DELETE_GROUP,
            Permissions::STRUCTURE_EDIT_GROUP,
            Permissions::STRUCTURE_LIST,
            Permissions::STRUCTURE_LIST_GROUP,
            Permissions::STRUCTURE_VIEW_GROUP,
        ]
    ],
    Roles::ROLE_STRUCTUREMAIN => [
        'type' => 1,
        'description' => 'Структуры. Только свои',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::STRUCTURE_DELETE_MAIN,
            Permissions::STRUCTURE_EDIT_MAIN,
            Permissions::STRUCTURE_LIST_MAIN,
            Permissions::STRUCTURE_VIEW_MAIN,
        ]
    ],
    Roles::ROLE_TEMPALTEADD => [
        'type' => 1,
        'description' => 'Шаблоны. Создание'],
    Roles::ROLE_TEMPATEGROUP => [
        'type' => 1,
        'description' => 'Шаблоны. Своя группа'],
    Roles::ROLE_TEMPLATEALL => [
        'type' => 1,
        'description' => 'Шаблоны. Своя и подчиненные группы',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::TEMPLATE_DELETE_ALL,
            Permissions::TEMPLATE_EDIT_ALL,
            Permissions::TEMPLATE_LIST,
            Permissions::TEMPLATE_LIST_ALL,
            Permissions::TEMPLATE_VIEW_ALL,
        ]
    ],
    Roles::ROLE_TEMPLATEDELETEDALL => [
        'type' => 1,
        'description' => 'Шаблоны. Удаленные. Своя и подчиненные группы',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::TEMPLATE_ENABLE_ALL,
        ]
    ],
    Roles::ROLE_TEMPLATEDELETEDGROUP => [
        'type' => 1,
        'description' => 'Шаблоны. Удаленные. Своя группа',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::TEMPLATE_ENABLE_GROUP,
        ]
    ],
    Roles::ROLE_TEMPLATEDELETEDMAIN => [
        'type' => 1,
        'description' => 'Шаблоны. Удаленные. Только свои',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::TEMPLATE_ENABLE_MAIN,
        ]
    ],
    Roles::ROLE_TEMPLATEMAIN => [
        'type' => 1,
        'description' => 'Шаблоны. Только свои',
        'children' => [
            Permissions::REPORT_INCLUDES,
            Permissions::TEMPLATE_DELETE_MAIN,
            Permissions::TEMPLATE_EDIT_MAIN,
            Permissions::TEMPLATE_LIST_MAIN,
            Permissions::TEMPLATE_VIEW_MAIN,
        ]
    ],





    Permissions::ADMIN_GROUP => [
        'type' => 2,
        'description' => 'Управление группами'],
    Permissions::ADMIN_GROUPTYPE => [
        'type' => 2,
        'description' => 'Управление типами групп'],
    Permissions::ADMIN_INCLUDES => [
        'type' => 2,
        'description' => 'Раздел с административными настройками'],
    Permissions::ADMIN_LOG => [
        'type' => 2,
        'description' => 'Просмотр логов'],
    Permissions::ADMIN_QUEUE => [
        'type' => 2,
        'description' => 'Просмотр очередей'],
    Permissions::ADMIN_QUEUE_SYSTEM => [
        'type' => 2,
        'description' => 'Просмотр очередей. Системная'],
    Permissions::ADMIN_QUEUE_TEMPLATE_LIST => [
        'type' => 2,
        'description' => 'Просмотр очередей. Формирование шаблонов'],
    Permissions::ADMIN_QUEUE_TEMPLATE_LIST_ALL => [
        'type' => 2,
        'description' => 'Просмотр очередей. Формирование шаблонов. Своя и подчиненные группы',
        'ruleName' =>'checkAll'],
    Permissions::ADMIN_QUEUE_TEMPLATE_LIST_GROUP => [
        'type' => 2,
        'description' => 'Просмотр очередей. Формирование шаблонов. Своя группа',
        'ruleName' =>'checkGroup'],
    Permissions::ADMIN_SETTING => [
        'type' => 2,
        'description' => 'Управление настройками'],
    Permissions::ADMIN_USER_CREATE => [
        'type' => 2,
        'description' => 'Пользователи. Добавление'],
    Permissions::ADMIN_USER_DELETE_ALL => [
        'type' => 2,
        'description' => 'Пользователи. Удаление. Своя и подчиненные группы',
        'ruleName' =>'checkUserAll',
        'children' => [
            Permissions::ADMIN_USER_VIEW_ALL,
        ]
    ],
    Permissions::ADMIN_USER_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Пользователи. Удаление. Своя группа',
        'ruleName' =>'checkUserGroup',
        'children' => [
            Permissions::ADMIN_USER_VIEW_GROUP,
        ]
    ],
    Permissions::ADMIN_USER_EDIT_ALL => [
        'type' => 2,
        'description' => 'Пользователи. Редактирование. Своя и подчиненные группы',
        'ruleName' =>'checkUserAll',
        'children' => [
            Permissions::ADMIN_USER_VIEW_ALL,
        ]
    ],
    Permissions::ADMIN_USER_EDIT_GROUP => [
        'type' => 2,
        'description' => 'Пользователи. Редактирование. Своя группа',
        'ruleName' =>'checkUserGroup',
        'children' => [
            Permissions::ADMIN_USER_VIEW_GROUP,
        ]
    ],
    Permissions::ADMIN_USER_ENABLE_ALL => [
        'type' => 2,
        'description' => 'Пользователи. Восстановление. Своя и подчиненные группы',
        'ruleName' =>'checkUserDeleteAll',
        'children' => [
            Permissions::ADMIN_USER_VIEW_DELETE_ALL,
        ]
    ],
    Permissions::ADMIN_USER_ENABLE_GROUP => [
        'type' => 2,
        'description' => 'Пользователи. Восстановление. Своя группа',
        'ruleName' =>'checkUserDeleteGroup',
        'children' => [
            Permissions::ADMIN_USER_VIEW_DELETE_GROUP,
        ]
    ],
    Permissions::ADMIN_USER_LIST => [
        'type' => 2,
        'description' => 'Пользователи. Список'],
    Permissions::ADMIN_USER_LIST_ALL => [
        'type' => 2,
        'description' => 'Пользователи. Список. Своя и подчиненные группы',
        'ruleName' =>'checkUserAll'],
    Permissions::ADMIN_USER_LIST_GROUP => [
        'type' => 2,
        'description' => 'Пользователи. Список. Своя группа',
        'ruleName' =>'checkUserGroup'],
    Permissions::ADMIN_USER_VIEW_ALL => [
        'type' => 2,
        'description' => 'Пользователи. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkUserAll',
        'children' => [
            Permissions::ADMIN_USER_LIST_ALL,
        ]
    ],
    Permissions::ADMIN_USER_VIEW_DELETE_ALL => [
        'type' => 2,
        'description' => 'Пользователи. Удаленные. Просмотр. Своя и подчиненные группы',
    ],
    Permissions::ADMIN_USER_VIEW_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Пользователи. Удаленные. Просмотр. Своя группа',
        'ruleName' =>'checkUserDeleteGroup',
        'children' => [
            Permissions::ADMIN_USER_LIST,
        ]
    ],
    Permissions::ADMIN_USER_VIEW_GROUP => [
        'type' => 2,
        'description' => 'Пользователи. Просмотр. Своя группа',
        'ruleName' =>'checkUserGroup',
        'children' => [
            Permissions::ADMIN_USER_LIST,
        ]
    ],
    Permissions::CONSTANT_CREATE => [
        'type' => 2,
        'description' => 'Константы. Добавление'],
    Permissions::CONSTANT_DELETE_ALL => [
        'type' => 2,
        'description' => 'Константы. Удаление. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::CONSTANT_VIEW_ALL,
        ]
    ],
    Permissions::CONSTANT_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Константы. Удаление. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::CONSTANT_VIEW_GROUP,
        ]
    ],
    Permissions::CONSTANT_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Константы. Удаление. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::CONSTANT_VIEW_MAIN,
        ]
    ],
    Permissions::CONSTANT_EDIT_ALL => [
        'type' => 2,
        'description' => 'Константы. Редактирование. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::CONSTANT_VIEW_ALL,
        ]
    ],
    Permissions::CONSTANT_EDIT_GROUP => [
        'type' => 2,
        'description' => 'Константы. Редактирование. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::CONSTANT_VIEW_GROUP,
        ]
    ],
    Permissions::CONSTANT_EDIT_MAIN => [
        'type' => 2,
        'description' => 'Константы. Редактирование. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::CONSTANT_VIEW_MAIN,
        ]
    ],
    Permissions::CONSTANT_ENABLE_ALL => [
        'type' => 2,
        'description' => 'Константы. Восстановление. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::CONSTANT_VIEW_DELETE_ALL,
        ]
    ],
    Permissions::CONSTANT_ENABLE_GROUP => [
        'type' => 2,
        'description' => 'Константы. Восстановление. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::CONSTANT_VIEW_DELETE_GROUP,
        ]
    ],
    Permissions::CONSTANT_ENABLE_MAIN => [
        'type' => 2,
        'description' => 'Константы. Восстановление. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::CONSTANT_VIEW_DELETE_MAIN,
        ]
    ],
    Permissions::CONSTANT_LIST => [
        'type' => 2,
        'description' => 'Константы. Список'],
    Permissions::CONSTANT_LIST_ALL => [
        'type' => 2,
        'description' => 'Константы. Список. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::CONSTANT_LIST_GROUP,
        ]
    ],
    Permissions::CONSTANT_LIST_GROUP => [
        'type' => 2,
        'description' => 'Константы. Список. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::CONSTANT_LIST_MAIN,
        ]
    ],
    Permissions::CONSTANT_LIST_MAIN => [
        'type' => 2,
        'description' => 'Константы. Список. Только свои',
        'ruleName' =>'checkMain'],
    Permissions::CONSTANT_VIEW_ALL => [
        'type' => 2,
        'description' => 'Константы. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::CONSTANT_LIST_ALL,
        ]
    ],
    Permissions::CONSTANT_VIEW_DELETE_ALL => [
        'type' => 2,
        'description' => 'Константы. Удаленные. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::CONSTANT_LIST_ALL,
        ]
    ],
    Permissions::CONSTANT_VIEW_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Константы. Удаленные. Просмотр. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::CONSTANT_LIST_GROUP,
        ]
    ],
    Permissions::CONSTANT_VIEW_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Константы. Удаленные. Просмотр. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::CONSTANT_LIST_MAIN,
        ]
    ],
    Permissions::CONSTANT_VIEW_GROUP => [
        'type' => 2,
        'description' => 'Константы. Просмотр. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::CONSTANT_LIST_GROUP,
        ]
    ],
    Permissions::CONSTANT_VIEW_MAIN => [
        'type' => 2,
        'description' => 'Константы. Просмотр. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::CONSTANT_LIST_MAIN,
        ]
    ],
    Permissions::CONSTANTRULE_CREATE => [
        'type' => 2,
        'description' => 'Правила сложения. Добавление'],
    Permissions::CONSTANTRULE_DELETE_ALL => [
        'type' => 2,
        'description' => 'Правила сложения. Удаление. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_ALL,
        ]
    ],
    Permissions::CONSTANTRULE_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Правила сложения. Удаление. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_GROUP,
        ]
    ],
    Permissions::CONSTANTRULE_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Правила сложения. Удаление. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_MAIN,
        ]
    ],
    Permissions::CONSTANTRULE_EDIT_ALL => [
        'type' => 2,
        'description' => 'Правила сложения. Редактирование. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_ALL,
        ]
    ],
    Permissions::CONSTANTRULE_EDIT_GROUP => [
        'type' => 2,
        'description' => 'Правила сложения. Редактирование. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_GROUP,
        ]
    ],
    Permissions::CONSTANTRULE_EDIT_MAIN => [
        'type' => 2,
        'description' => 'Правила сложения. Редактирование. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_MAIN,
        ]
    ],
    Permissions::CONSTANTRULE_ENABLE_ALL => [
        'type' => 2,
        'description' => 'Правила сложения. Восстановление. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_DELETE_ALL,
        ]
    ],
    Permissions::CONSTANTRULE_ENABLE_GROUP => [
        'type' => 2,
        'description' => 'Правила сложения. Восстановление. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_DELETE_GROUP,
        ]
    ],
    Permissions::CONSTANTRULE_ENABLE_MAIN => [
        'type' => 2,
        'description' => 'Правила сложения. Восстановление. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::CONSTANTRULE_VIEW_DELETE_MAIN,
        ]
    ],
    Permissions::CONSTANTRULE_LIST => [
        'type' => 2,
        'description' => 'Правила сложения. Список'],
    Permissions::CONSTANTRULE_LIST_ALL => [
        'type' => 2,
        'description' => 'Правила сложения. Список. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::CONSTANTRULE_LIST_GROUP,
        ]
    ],
    Permissions::CONSTANTRULE_LIST_GROUP => [
        'type' => 2,
        'description' => 'Правила сложения. Список. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::CONSTANTRULE_LIST_MAIN,
        ]
    ],
    Permissions::CONSTANTRULE_LIST_MAIN => [
        'type' => 2,
        'description' => 'Правила сложения. Список. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::CONSTANTRULE_LIST,
        ]
    ],
    Permissions::CONSTANTRULE_VIEW_ALL => [
        'type' => 2,
        'description' => 'Правила сложения. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::CONSTANTRULE_LIST_ALL,
        ]
    ],
    Permissions::CONSTANTRULE_VIEW_DELETE_ALL => [
        'type' => 2,
        'description' => 'Правила сложения. Удаленные. Просмотр. Созданные пользователями своей и подчиненных групп',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::CONSTANTRULE_LIST_ALL,
        ]
    ],
    Permissions::CONSTANTRULE_VIEW_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Правила сложения. Удаленные. Просмотр. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::CONSTANTRULE_LIST_GROUP,
        ]
    ],
    Permissions::CONSTANTRULE_VIEW_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Правила сложения. Удаленные. Просмотр. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::CONSTANTRULE_LIST_MAIN,
        ]
    ],
    Permissions::CONSTANTRULE_VIEW_GROUP => [
        'type' => 2,
        'description' => 'Правила сложения. Просмотр. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::CONSTANTRULE_LIST_GROUP,
        ]
    ],
    Permissions::CONSTANTRULE_VIEW_MAIN => [
        'type' => 2,
        'description' => 'Правила сложения. Просмотр. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::CONSTANTRULE_LIST_MAIN,
        ]
    ],
    Permissions::DATA_CHANGE_ALL => [
        'type' => 2,
        'description' => 'Сведения. Просмотр изменений. Своя и подчиненные группы',
        'ruleName' =>'checkAll'],
    Permissions::DATA_CHANGE_GROUP => [
        'type' => 2,
        'description' => 'Сведения. Просмотр изменений. Своя группа',
        'ruleName' =>'checkGroup'],
    Permissions::DATA_CHANGE_MAIN => [
        'type' => 2,
        'description' => 'Сведения. Просмотр изменений. Только свои',
        'ruleName' =>'checkMain'],
    Permissions::DATA_CHECKFULL => [
        'type' => 2,
        'description' => 'Сведения. Просмотр полноты передачи'],
    Permissions::DATA_CREATEFOR => [
        'type' => 2,
        'description' => 'Сведения. Добавление за подчиненную группу'],
    Permissions::DATA_DELETE_ALL => [
        'type' => 2,
        'description' => 'Сведения. Удаление. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::DATA_VIEW_ALL,
        ]
    ],
    Permissions::DATA_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Сведения. Удаление. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::DATA_VIEW_GROUP,
        ]
    ],
    Permissions::DATA_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Сведения. Удаление. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::DATA_VIEW_MAIN,
        ]
    ],
    Permissions::DATA_EDIT_ALL => [
        'type' => 2,
        'description' => 'Сведения. Редактирование. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::DATA_VIEW_ALL,
        ]
    ],
    Permissions::DATA_EDIT_GROUP => [
        'type' => 2,
        'description' => 'Сведения. Редактирование. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::DATA_VIEW_GROUP,
        ]
    ],
    Permissions::DATA_EDIT_MAIN => [
        'type' => 2,
        'description' => 'Сведения. Редактирование. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::DATA_VIEW_MAIN,
        ]
    ],
    Permissions::DATA_ENABLE_ALL => [
        'type' => 2,
        'description' => 'Сведения. Восстановление. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::DATA_VIEW_DELETE_ALL,
        ]
    ],
    Permissions::DATA_ENABLE_GROUP => [
        'type' => 2,
        'description' => 'Сведения. Восстановление. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::DATA_VIEW_DELETE_GROUP,
        ]
    ],
    Permissions::DATA_ENABLE_MAIN => [
        'type' => 2,
        'description' => 'Сведения. Восстановление. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::DATA_VIEW_DELETE_MAIN,
        ]
    ],
    Permissions::DATA_LIST => [
        'type' => 2,
        'description' => 'Сведения. Список'],
    Permissions::DATA_LIST_ALL => [
        'type' => 2,
        'description' => 'Сведения. Список. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::DATA_LIST_GROUP,
        ]
    ],
    Permissions::DATA_LIST_GROUP => [
        'type' => 2,
        'description' => 'Сведения. Список. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::DATA_LIST_MAIN,
        ]
    ],
    Permissions::DATA_LIST_MAIN => [
        'type' => 2,
        'description' => 'Сведения. Список. Только свои'],
    Permissions::DATA_SEND => [
        'type' => 2,
        'description' => 'Сведения. Передача'],
    Permissions::DATA_SEND_ALL => [
        'type' => 2,
        'description' => 'Передача сведений за подчиненные группы',
        'ruleName' =>'checkDataAll'],
    Permissions::DATA_VIEW_ALL => [
        'type' => 2,
        'description' => 'Сведения. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::DATA_LIST_ALL,
        ]
    ],
    Permissions::DATA_VIEW_DELETE_ALL => [
        'type' => 2,
        'description' => 'Сведения. Удаленные. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::DATA_LIST_ALL,
        ]
    ],
    Permissions::DATA_VIEW_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Сведения. Удаленные. Просмотр. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::DATA_LIST_GROUP,
        ]
    ],
    Permissions::DATA_VIEW_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Сведения. Удаленные. Просмотр. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::DATA_LIST_MAIN,
        ]
    ],
    Permissions::DATA_VIEW_GROUP => [
        'type' => 2,
        'description' => 'Сведения. Просмотр. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::DATA_LIST_GROUP,
        ]
    ],
    Permissions::DATA_VIEW_MAIN => [
        'type' => 2,
        'description' => 'Сведения. Просмотр. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::DATA_LIST_MAIN,
        ]
    ],
    Permissions::REPORT_CREATE => [
        'type' => 2,
        'description' => 'Отчеты. Добавление'],
    Permissions::REPORT_DELETE_ALL => [
        'type' => 2,
        'description' => 'Отчеты. Удаление. Своя и подчиненные группы',
        'ruleName' =>'checkAll'],
    Permissions::REPORT_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Отчеты. Удаление. Своя группа',
        'ruleName' =>'checkGroup'],
    Permissions::REPORT_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Отчеты. Удаление. Только свои',
        'ruleName' =>'checkMain'],
    Permissions::REPORT_EDIT_ALL => [
        'type' => 2,
        'description' => 'Отчеты. Редактирование. Своя и подчиненные группы',
        'ruleName' =>'checkAll'],
    Permissions::REPORT_EDIT_GROUP => [
        'type' => 2,
        'description' => 'Отчеты. Редактирование. Своя группа',
        'ruleName' =>'checkGroup'],
    Permissions::REPORT_EDIT_MAIN => [
        'type' => 2,
        'description' => 'Отчеты. Редактирование. Только свои',
        'ruleName' =>'checkMain'],
    Permissions::REPORT_ENABLE_ALL => [
        'type' => 2,
        'description' => 'Отчеты. Восстановление. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll'],
    Permissions::REPORT_ENABLE_GROUP => [
        'type' => 2,
        'description' => 'Отчеты. Восстановление. Своя группа',
        'ruleName' =>'checkDeleteGroup'],
    Permissions::REPORT_ENABLE_MAIN => [
        'type' => 2,
        'description' => 'Отчеты. Восстановление. Только свои',
        'ruleName' =>'checkDeleteMain'],
    Permissions::REPORT_INCLUDES => [
        'type' => 2,
        'description' => 'Раздел с настройкой компонентов отчетов'],
    Permissions::REPORT_LIST => [
        'type' => 2,
        'description' => 'Отчеты. Список'],
    Permissions::REPORT_LIST_ALL => [
        'type' => 2,
        'description' => 'Отчеты. Список. Своя и подчиненные группы',
        'ruleName' =>'checkAll'],
    Permissions::REPORT_LIST_GROUP => [
        'type' => 2,
        'description' => 'Отчеты. Список. Своя группа',
        'ruleName' =>'checkGroup'],
    Permissions::REPORT_VIEW_ALL => [
        'type' => 2,
        'description' => 'Отчеты. Просмотр. Своя и починенные группы',
        'ruleName' =>'checkAll'],
    Permissions::REPORT_VIEW_DELETE_ALL => [
        'type' => 2,
        'description' => 'Отчеты. Удаленные. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll'],
    Permissions::REPORT_VIEW_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Отчеты. Удаленные. Просмотр. Своя группа',
        'ruleName' =>'checkDeleteGroup'],
    Permissions::REPORT_VIEW_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Отчеты. Удаленные. Просмотр. Только свои',
        'ruleName' =>'checkDeleteMain'],
    Permissions::REPORT_VIEW_GROUP => [
        'type' => 2,
        'description' => 'Отчеты. Просмотр. Своя группа',
        'ruleName' =>'checkGroup'],
    Permissions::REPORT_VIEW_MAIN => [
        'type' => 2,
        'description' => 'Отчеты. Просмотр. Только свои',
        'ruleName' =>'checkMain'],
    Permissions::STATISTIC => [
        'type' => 2,
        'description' => 'Формирование отчетов'],
    Permissions::STRUCTURE_CREATE => [
        'type' => 2,
        'description' => 'Структуры передачи. Добавление'],
    Permissions::STRUCTURE_DELETE_ALL => [
        'type' => 2,
        'description' => 'Структуры передачи. Удаление. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::STRUCTURE_VIEW_ALL,
        ]
    ],
    Permissions::STRUCTURE_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Структуры передачи. Удаление. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::STRUCTURE_VIEW_GROUP,
        ]
    ],
    Permissions::STRUCTURE_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Структуры передачи. Удаление. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::STRUCTURE_VIEW_MAIN,
        ]
    ],
    Permissions::STRUCTURE_EDIT_ALL => [
        'type' => 2,
        'description' => 'Структуры передачи. Редактирование. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::STRUCTURE_VIEW_ALL,
        ]
    ],
    Permissions::STRUCTURE_EDIT_GROUP => [
        'type' => 2,
        'description' => 'Структуры передачи. Редактирование. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::STRUCTURE_VIEW_GROUP,
        ]
    ],
    Permissions::STRUCTURE_EDIT_MAIN => [
        'type' => 2,
        'description' => 'Структуры передачи. Редактирование. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::STRUCTURE_VIEW_MAIN,
        ]
    ],
    Permissions::STRUCTURE_ENABLE_ALL => [
        'type' => 2,
        'description' => 'Структуры передачи. Восстановление. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::STRUCTURE_VIEW_DELETE_ALL,
        ]
    ],
    Permissions::STRUCTURE_ENABLE_GROUP => [
        'type' => 2,
        'description' => 'Структуры передачи. Восстановление. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::STRUCTURE_VIEW_DELETE_GROUP,
        ]
    ],
    Permissions::STRUCTURE_ENABLE_MAIN => [
        'type' => 2,
        'description' => 'Структуры передачи. Восстановление. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::STRUCTURE_VIEW_DELETE_MAIN,
        ]
    ],
    Permissions::STRUCTURE_LIST => [
        'type' => 2,
        'description' => 'Структуры передачи. Список'],
    Permissions::STRUCTURE_LIST_ALL => [
        'type' => 2,
        'description' => 'Структуры передачи. Список. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::STRUCTURE_LIST_GROUP,
        ]
    ],
    Permissions::STRUCTURE_LIST_GROUP => [
        'type' => 2,
        'description' => 'Структуры передачи. Список. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::STRUCTURE_LIST_MAIN,
        ]
    ],
    Permissions::STRUCTURE_LIST_MAIN => [
        'type' => 2,
        'description' => 'Структуры передачи. Список. Только свои',
        'children' => [
            Permissions::STRUCTURE_LIST,
        ]
    ],
    Permissions::STRUCTURE_VIEW_ALL => [
        'type' => 2,
        'description' => 'Структуры передачи. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::STRUCTURE_LIST_ALL,
        ]
    ],
    Permissions::STRUCTURE_VIEW_DELETE_ALL => [
        'type' => 2,
        'description' => 'Структуры передачи. Удаленные. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::STRUCTURE_LIST_ALL,
        ]
    ],
    Permissions::STRUCTURE_VIEW_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Структуры передачи. Удаленные. Просмотр. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::STRUCTURE_LIST_GROUP,
        ]
    ],
    Permissions::STRUCTURE_VIEW_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Структуры передачи. Удаленные. Просмотр. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::STRUCTURE_LIST_MAIN,
        ]
    ],
    Permissions::STRUCTURE_VIEW_GROUP => [
        'type' => 2,
        'description' => 'Структуры передачи. Просмотр. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::STRUCTURE_LIST_GROUP,
        ]
    ],
    Permissions::STRUCTURE_VIEW_MAIN => [
        'type' => 2,
        'description' => 'Структуры передачи. Просмотр. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::STRUCTURE_LIST_MAIN,
        ]
    ],
    Permissions::TEMPLATE_CREATE => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Добавление'],
    Permissions::TEMPLATE_DELETE_ALL => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Удаление. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::TEMPLATE_VIEW_ALL,
        ]
    ],
    Permissions::TEMPLATE_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Удаление. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::TEMPLATE_VIEW_GROUP,
        ]
    ],
    Permissions::TEMPLATE_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Удаление. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::TEMPLATE_VIEW_MAIN,
        ]
    ],
    Permissions::TEMPLATE_EDIT_ALL => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Редактирование. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::TEMPLATE_VIEW_ALL,
        ]
    ],
    Permissions::TEMPLATE_EDIT_GROUP => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Редактирование. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::TEMPLATE_VIEW_GROUP,
        ]
    ],
    Permissions::TEMPLATE_EDIT_MAIN => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Редактирование. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::TEMPLATE_VIEW_MAIN,
        ]
    ],
    Permissions::TEMPLATE_ENABLE_ALL => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Восстановление. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::TEMPLATE_VIEW_DELETE_ALL,
        ]
    ],
    Permissions::TEMPLATE_ENABLE_GROUP => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Восстановление. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::TEMPLATE_VIEW_DELETE_GROUP,
        ]
    ],
    Permissions::TEMPLATE_ENABLE_MAIN => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Восстановление. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::TEMPLATE_VIEW_DELETE_MAIN,
        ]
    ],
    Permissions::TEMPLATE_LIST => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Список'],
    Permissions::TEMPLATE_LIST_ALL => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Список. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::TEMPLATE_LIST_GROUP,
        ]
    ],
    Permissions::TEMPLATE_LIST_GROUP => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Список. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::TEMPLATE_LIST_MAIN,
        ]
    ],
    Permissions::TEMPLATE_LIST_MAIN => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Список. Только свои',
        'children' => [
            Permissions::TEMPLATE_LIST,
        ]
    ],
    Permissions::TEMPLATE_VIEW_ALL => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkAll',
        'children' => [
            Permissions::TEMPLATE_LIST_ALL,
        ]
    ],
    Permissions::TEMPLATE_VIEW_DELETE_ALL => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Удаленные. Просмотр. Своя и подчиненные группы',
        'ruleName' =>'checkDeleteAll',
        'children' => [
            Permissions::TEMPLATE_LIST_ALL,
        ]
    ],
    Permissions::TEMPLATE_VIEW_DELETE_GROUP => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Удаленные. Просмотр. Своя группа',
        'ruleName' =>'checkDeleteGroup',
        'children' => [
            Permissions::TEMPLATE_LIST_GROUP,
        ]
    ],
    Permissions::TEMPLATE_VIEW_DELETE_MAIN => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Удаленные. Просмотр. Только свои',
        'ruleName' =>'checkDeleteMain',
        'children' => [
            Permissions::TEMPLATE_LIST_MAIN,
        ]
    ],
    Permissions::TEMPLATE_VIEW_GROUP => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Просмотр. Своя группа',
        'ruleName' =>'checkGroup',
        'children' => [
            Permissions::TEMPLATE_LIST_GROUP,
        ]
    ],
    Permissions::TEMPLATE_VIEW_MAIN => [
        'type' => 2,
        'description' => 'Шаблоны формирования. Просмотр. Только свои',
        'ruleName' =>'checkMain',
        'children' => [
            Permissions::TEMPLATE_LIST_MAIN,
        ]
    ],
];
