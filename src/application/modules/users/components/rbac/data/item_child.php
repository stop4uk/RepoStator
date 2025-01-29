<?php

use app\modules\users\components\rbac\items\{
    Roles,
    Permissions
};

return [
    Roles::ADMINISTRATOR => [
        'type' => 1,
        'description' => 'Администратор',
    ],
    Roles::CLIENT => [
        'type' => 1,
        'description' => 'Роль "Клиент"',
        'children' => [
            Roles::RC__LCL__BOOKING,
            Roles::RC__ORDER,
            Roles::RC__ORDERCARDELIVERY,
            Roles::RC__ORDERCUSTOMPROCESSING,
            Roles::RC__REQUEST,
        ],
    ],
    Roles::MANAGER => [
        'type' => 1,
        'description' => 'Роль "Менеджер"',
        'children' => [
            Roles::RA__CONTRACT,
            Roles::RA__DIR__CONTAINERTYPE,
            Roles::RA__DIR__CONTAINERTYPE_DELETE,
            Roles::RA__DIR__GEO__CITY,
            Roles::RA__DIR__GEO__COUNTRY,
            Roles::RA__DIR__GEO__COUNTRY_DELETE,
            Roles::RA__DIR__INCOTERM,
            Roles::RA__DIR__INCOTERM_DELETE,
            Roles::RA__DIR__LCLAGENT,
            Roles::RA__DIR__RAILROAD,
            Roles::RA__DIR__RAILROAD_DELETE,
            Roles::RA__DIR__ROUTE,
            Roles::RA__DIR__ROUTE_DELETE,
            Roles::RA__DIR__STOCK,
            Roles::RA__DIR__STOCK_DELETE,
            Roles::RA__DIR__TRANSIT,
            Roles::RA__DIR__TRANSIT_DELETE,
            Roles::RA__DIR__TRANSPORTTYPE,
            Roles::RA__DIR__TRANSPORTTYPE_DELETE,
            Roles::RA__FINANCE__INVOICE,
            Roles::RA__FINANCE__INVOICE_DELETE,
            Roles::RA__FINANCE__INVOICE_HISTORY,
            Roles::RA__FINANCE__REPORT,
            Roles::RA__LCL__BOOKING,
            Roles::RA__LCL__BOOKING_DELETE,
            Roles::RA__LCL__BOOKING_HISTORY,
            Roles::RA__LCL__CONTAINER,
            Roles::RA__LCL__CONTAINER_BOOKING,
            Roles::RA__LCL__CONTAINER_DELETE,
            Roles::RA__LCL__CONTAINER_HISTORY,
            Roles::RA__ORDER,
            Roles::RA__ORDER_CHANGESTATUS,
            Roles::RA__ORDERCARDELIVERY,
            Roles::RA__ORDERCARDELIVERY_CHANGESTATUS,
            Roles::RA__ORDERCARDELIVERY_DELETE,
            Roles::RA__ORDERCARDELIVERY_HISTORY,
            Roles::RA__ORDERCUSTOMPROCESSING,
            Roles::RA__ORDERCUSTOMPROCESSING_CHANGESTATUS,
            Roles::RA__ORDERCUSTOMPROCESSING_DELETE,
            Roles::RA__ORGANIZATION,
            Roles::RA__ORGANIZATION_DELETE,
            Roles::RA__REQUEST,
            Roles::RA__REQUEST_CHANGESTATUS,
            Roles::RA__REQUEST_DELETE,
            Roles::RA__REQUEST_HISTORY,
            Roles::RA__USER__CLIENT,
            Roles::RA__USER__CLIENT_DELETE,
            Roles::RA__TRANSPORTATION_ORDER,
            Roles::RA__TRANSPORTATION_ORDER_CHANGESTATUS,
            Roles::RA__TRANSPORTATION_ORDER_DELETE,
            Roles::RA__TRANSPORTATION_ORDER_HISTORY,
            Permissions::ADMIN__DASHBOARD,
            Permissions::ADMIN__DIRECTORY__TERMINAL_VIEW,
            Permissions::ADMIN__ORGANIZATION_VIEW,
        ],
    ],

    Roles::CLIENT_MANAGER => [
        'type' => 1,
        'description' => 'Роль "Менеджер клиента"',
        'children' => [
            Roles::RA__DIR__STOCK,
            Roles::RA__DIR__STOCK_DELETE,
            Roles::RA__ORDERCARDELIVERY,
            Roles::RA__ORDERCARDELIVERY_CHANGESTATUS,
            Roles::RA__ORDERCARDELIVERY_HISTORY,
            Permissions::ADMIN__DASHBOARD,
            Permissions::ADMIN__DIRECTORY__TERMINAL_VIEW,
        ],
    ],

    Roles::LOGIST => [
        'type' => 1,
        'description' => 'Роль "Логист"',
        'children' => [
            Roles::RA__LOGISTICS__LIST,
            Permissions::ADMIN__LOGISTICS__UPDATE_RATES,
            Permissions::ADMIN__LOGISTICS__DELETE_RATES,
            Permissions::ADMIN__LOGISTICS__TAKE,
            Permissions::ADMIN__LOGISTICS__CHANGESTATUS,
        ],
    ],

    Roles::RA__CONTRACT => [
        'type' => 1,
        'description' => 'РА. Контракты',
        'children' => [
            Permissions::ADMIN__CONTRACT_LIST,
            Permissions::ADMIN__CONTRACT_UPDATE,
        ],
    ],
    Roles::RA__DIR__CONTAINERTYPE => [
        'type' => 1,
        'description' => 'РА. Справочники. Типы контейнеров',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_CREATE,
            Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_UPDATE,
        ],
    ],
    Roles::RA__DIR__CONTAINERTYPE_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. Типы контейнеров. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_DELETE,
        ],
    ],
    Roles::RA__DIR__CURRENCY => [
        'type' => 1,
        'description' => 'РА. Справочники. Валюты',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CURRENCY_CREATE,
            Permissions::ADMIN__DIRECTORY__CURRENCY_UPDATE,
        ],
    ],
    Roles::RA__DIR__GEO__CITY => [
        'type' => 1,
        'description' => 'РА. Справочники. Города',
        'children' => [
            Permissions::ADMIN__DIRECTORY__GEO__CITY_CREATE,
            Permissions::ADMIN__DIRECTORY__GEO__CITY_UPDATE,
        ],
    ],
    Roles::RA__DIR__GEO__COUNTRY => [
        'type' => 1,
        'description' => 'РА. Справочники. Страны',
        'children' => [
            Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_CREATE,
            Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_UPDATE,
        ],
    ],
    Roles::RA__DIR__GEO__COUNTRY_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. Страны. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_DELETE,
        ],
    ],
    Roles::RA__DIR__INCOTERM => [
        'type' => 1,
        'description' => 'РА. Справочники. Условия Инкотерм',
        'children' => [
            Permissions::ADMIN__DIRECTORY__INCOTERM_CREATE,
            Permissions::ADMIN__DIRECTORY__INCOTERM_UPDATE,
        ],
    ],
    Roles::RA__DIR__INCOTERM_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. Условия Инкотерм. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__INCOTERM_DELETE,
        ],
    ],
    Roles::RA__DIR__LCLAGENT => [
        'type' => 1,
        'description' => 'РА. Справочники. Сборные грузы. Агенты',
        'children' => [
            Permissions::ADMIN__DIRECTORY__LCLAGENT_CREATE,
            Permissions::ADMIN__DIRECTORY__LCLAGENT_UPDATE,
        ],
    ],
    Roles::RA__DIR__RAILROAD => [
        'type' => 1,
        'description' => 'РА. Справочники. ЖД станции',
        'children' => [
            Permissions::ADMIN__DIRECTORY__RAILROAD_CREATE,
            Permissions::ADMIN__DIRECTORY__RAILROAD_UPDATE,
        ],
    ],
    Roles::RA__DIR__RAILROAD_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. ЖД станции. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__RAILROAD_DELETE,
        ],
    ],
    Roles::RA__DIR__ROUTE => [
        'type' => 1,
        'description' => 'РА. Справочники. Маршруты',
        'children' => [
            Permissions::ADMIN__DIRECTORY__ROUTE_CREATE,
            Permissions::ADMIN__DIRECTORY__ROUTE_UPDATE,
        ],
    ],
    Roles::RA__DIR__ROUTE_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. Маршруты. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__ROUTE_DELETE,
        ],
    ],
    Roles::RA__DIR__SERVICE => [
        'type' => 1,
        'description' => 'РА. Справочники. Услуги',
        'children' => [
            Permissions::ADMIN__DIRECTORY__SERVICE_CREATE,
            Permissions::ADMIN__DIRECTORY__SERVICE_UPDATE,
        ],
    ],
    Roles::RA__DIR__STOCK => [
        'type' => 1,
        'description' => 'РА. Справочники. Стоки',
        'children' => [
            Permissions::ADMIN__DIRECTORY__STOCK_CREATE,
            Permissions::ADMIN__DIRECTORY__STOCK_UPDATE,
        ],
    ],
    Roles::RA__DIR__STOCK_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. Стоки. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__STOCK_DELETE,
        ],
    ],
    Roles::RA__DIR__TERMINAL => [
        'type' => 1,
        'description' => 'РА. Справочники. Терминалы',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TERMINAL_CREATE,
            Permissions::ADMIN__DIRECTORY__TERMINAL_UPDATE,
        ],
    ],
    Roles::RA__DIR__TERMINAL_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. Терминалы. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TERMINAL_DELETE,
        ],
    ],
    Roles::RA__DIR__TRANSIT => [
        'type' => 1,
        'description' => 'РА. Справочники. Погран. переходы',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSIT_CREATE,
            Permissions::ADMIN__DIRECTORY__TRANSIT_UPDATE,
            Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_UPDATE,
        ],
    ],
    Roles::RA__DIR__TRANSIT_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. Погран. переходы. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSIT_DELETE,
        ],
    ],
    Roles::RA__DIR__TRANSPORTTYPE => [
        'type' => 1,
        'description' => 'РА. Справочники. Транспортные профили',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_CREATE,
        ],
    ],
    Roles::RA__DIR__TRANSPORTTYPE_DELETE => [
        'type' => 1,
        'description' => 'РА. Справочники. Транспортные профили. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_DELETE,
        ],
    ],
    Roles::RA__FINANCE__INVOICE => [
        'type' => 1,
        'description' => 'РА. Финансы. Счета',
        'children' => [
            Permissions::ADMIN__FINANCE__INVOICE_CREATE,
            Permissions::ADMIN__FINANCE__INVOICE_UPDATE,
        ],
    ],
    Roles::RA__FINANCE__INVOICE_DELETE => [
        'type' => 1,
        'description' => 'РА. Финансы. Счета. Удаление',
        'children' => [
            Permissions::ADMIN__FINANCE__INVOICE_DELETE,
        ],
    ],
    Roles::RA__FINANCE__INVOICE_HISTORY => [
        'type' => 1,
        'description' => 'РА. Финансы. Счета. История изменений',
        'children' => [
            Permissions::ADMIN__FINANCE__INVOICE_HISTORY,
        ],
    ],
    Roles::RA__FINANCE__REPORT => [
        'type' => 1,
        'description' => 'РА. Финансы. Отчеты',
        'children' => [
            Permissions::ADMIN__FINANCE__REPORT_CREATE,
            Permissions::ADMIN__FINANCE__REPORT_UPDATE,
        ],
    ],
    Roles::RA__LCL__BOOKING => [
        'type' => 1,
        'description' => 'РА. Сборные грузы. Заказы',
        'children' => [
            Permissions::ADMIN__LCL__BOOKING_CREATE,
            Permissions::ADMIN__LCL__BOOKING_HISTORY,
            Permissions::ADMIN__LCL__BOOKING_UPDATE,
        ],
    ],
    Roles::RA__LCL__BOOKING_DELETE => [
        'type' => 1,
        'description' => 'РА. Сборные грузы. Заказы. Удаление',
        'children' => [
            Permissions::ADMIN__LCL__BOOKING_DELETE,
        ],
    ],
    Roles::RA__LCL__BOOKING_HISTORY => [
        'type' => 1,
        'description' => 'РА. Сборные грузы. Заказы. История изменений',
        'children' => [
            Permissions::ADMIN__LCL__BOOKING_HISTORY,
        ],
    ],
    Roles::RA__LCL__CONTAINER => [
        'type' => 1,
        'description' => 'РА. Сборные грузы. Контейнеры',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_CREATE,
            Permissions::ADMIN__LCL__CONTAINER_HISTORY,
            Permissions::ADMIN__LCL__CONTAINER_UPDATE,
        ],
    ],
    Roles::RA__LCL__CONTAINER_BOOKING => [
        'type' => 1,
        'description' => 'РА. Сборные грузы. Контейнеры. Просмотр заказов',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_BOOKING,
        ],
    ],
    Roles::RA__LCL__CONTAINER_DELETE => [
        'type' => 1,
        'description' => 'РА. Сборные грузы. Контейнеры. Удаление',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_DELETE,
        ],
    ],
    Roles::RA__LCL__CONTAINER_HISTORY => [
        'type' => 1,
        'description' => 'РА. Сборные грузы. Контейнеры. История изменений',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_HISTORY,
        ],
    ],
    Roles::RA__ORDER => [
        'type' => 1,
        'description' => 'РА. ЖД перевозки',
        'children' => [
            Permissions::ADMIN__ORDER_UPDATE,
        ],
    ],
    Roles::RA__ORDER_CHANGESTATUS => [
        'type' => 1,
        'description' => 'РА. ЖД перевозки. Изменение статуса',
        'children' => [
            Permissions::ADMIN__ORDER_CHANGESTATUS,
        ],
    ],
    Roles::RA__ORDER_UPDATETRIP => [
        'type' => 1,
        'description' => 'РА. ЖД перевозки. Обновление маршрута',
        'children' => [
            Permissions::ADMIN__ORDER_UPDATETRIP,
        ],
    ],
    Roles::RA__ORDERCARDELIVERY => [
        'type' => 1,
        'description' => 'РА. Автодоставка',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_CREATE,
            Permissions::ADMIN__ORDERCARDELIVERY_HISTORY,
            Permissions::ADMIN__ORDERCARDELIVERY_UPDATE,
        ],
    ],
    Roles::RA__ORDERCARDELIVERY_CHANGECONTAINERCODE => [
        'type' => 1,
        'description' => 'РА. Атводоставка. Изменение номера контейнера',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_CHANGECONTAINERCODE,
        ],
    ],
    Roles::RA__ORDERCARDELIVERY_CHANGESTATUS => [
        'type' => 1,
        'description' => 'РА. Автодоставка. Изменение статуса',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_CHANGESTATUS,
        ],
    ],
    Roles::RA__ORDERCARDELIVERY_DELETE => [
        'type' => 1,
        'description' => 'РА. Автодоставка. Удаление',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_DELETE,
        ],
    ],
    Roles::RA__ORDERCARDELIVERY_HISTORY => [
        'type' => 1,
        'description' => 'РА. Автодоставка. История изменений',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_HISTORY,
        ],
    ],
    Roles::RA__ORDERCUSTOMPROCESSING => [
        'type' => 1,
        'description' => 'РА. Терминальная обработка',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_CREATE,
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_TRANSFERTRIP,
        ],
    ],
    Roles::RA__ORDERCUSTOMPROCESSING_CHANGESTATUS => [
        'type' => 1,
        'description' => 'РА. Терминальная обработка. Изменение статуса',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_CHANGESTATUS,
        ],
    ],
    Roles::RA__ORDERCUSTOMPROCESSING_DELETE => [
        'type' => 1,
        'description' => 'РА. Терминальная обработка. Удаление',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_DELETE,
        ],
    ],
    Roles::RA__ORGANIZATION => [
        'type' => 1,
        'description' => 'РА. Организации',
        'children' => [
            Permissions::ADMIN__ORGANIZATION_CREATE,
            Permissions::ADMIN__ORGANIZATION_UPDATE,
        ],
    ],
    Roles::RA__ORGANIZATION_DELETE => [
        'type' => 1,
        'description' => 'РА. Организации. Удаление',
        'children' => [
            Permissions::ADMIN__ORGANIZATION_DELETE,
        ],
    ],
    Roles::RA__REQUEST => [
        'type' => 1,
        'description' => 'РА. Запросы',
        'children' => [
            Permissions::ADMIN__REQUEST_CREATE,
            Permissions::ADMIN__REQUEST_UPDATE,
            Permissions::ADMIN__REQUEST_UPDATE_RATES,
            Permissions::ADMIN__REQUEST_DELETE_RATES,
        ],
    ],
    Roles::RA__REQUEST_CHANGESTATUS => [
        'type' => 1,
        'description' => 'РА. Запросы. Изменение статуса',
        'children' => [
            Permissions::ADMIN__REQUEST_CHANGESTATUS,
        ],
    ],
    Roles::RA__REQUEST_DELETE => [
        'type' => 1,
        'description' => 'РА. Запросы. Удаление',
        'children' => [
            Permissions::ADMIN__REQUEST_DELETE,
        ],
    ],
    Roles::RA__REQUEST_HISTORY => [
        'type' => 1,
        'description' => 'РА. Запросы. История изменений',
        'children' => [
            Permissions::ADMIN__REQUEST_HISTORY,
        ],
    ],
    Roles::RA__TRANSPORTATION_ORDER => [
        'type' => 1,
        'description' => 'РА. Заявки на перевозку',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_LIST,
            Permissions::ADMIN__TRANSPORTATION_ORDER_CREATE,
            Permissions::ADMIN__TRANSPORTATION_ORDER_UPDATE,
            Permissions::ADMIN__TRANSPORTATION_ORDER_UPDATE_RATES,
            Permissions::ADMIN__TRANSPORTATION_ORDER_DELETE_RATES,
        ],
    ],
    Roles::RA__TRANSPORTATION_ORDER_CHANGESTATUS => [
        'type' => 1,
        'description' => 'РА. Заявки на перевозку. Изменение статуса',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_CHANGE_STATUS,
        ],
    ],
    Roles::RA__TRANSPORTATION_ORDER_DELETE => [
        'type' => 1,
        'description' => 'РА. Заявки на перевозку. Удаление',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_DELETE,
        ],
    ],
    Roles::RA__TRANSPORTATION_ORDER_HISTORY => [
        'type' => 1,
        'description' => 'РА. Заявки на перевозку. История изменений',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_HISTORY,
        ],
    ],
    Roles::RA__LOGISTICS__LIST => [
        'type' => 1,
        'description' => 'РА. Кабинет логиста. Просмотр заявок',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
            Permissions::ADMIN__LOGISTICS__LIST,
        ]
    ],
    Roles::RA__USER__CLIENT => [
        'type' => 1,
        'description' => 'РА. Пользователи. Клиенты',
        'children' => [
            Permissions::ADMIN__DASHBOARD,
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_CLIENTS,
            Permissions::ADMIN__USER__CLIENT_CREATE,
            Permissions::ADMIN__USER__CLIENT_LIST,
            Permissions::ADMIN__USER__CLIENT_UPDATE,
            Permissions::ADMIN__USER__CLIENT_VIEW,
        ],
    ],
    Roles::RA__USER__CLIENT_DELETE => [
        'type' => 1,
        'description' => 'РА. Пользователи. Клиенты. Удаление',
        'children' => [
            Permissions::ADMIN__USER__CLIENT_DELETE,
        ],
    ],
    Roles::RA__USER__MANAGER => [
        'type' => 1,
        'description' => 'РА. Пользователи. Менеджеры',
        'children' => [
            Permissions::ADMIN__DASHBOARD,
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_CLIENTS,
            Permissions::ADMIN__USER__MANAGER_CREATE,
            Permissions::ADMIN__USER__MANAGER_LIST,
            Permissions::ADMIN__USER__MANAGER_UPDATE,
            Permissions::ADMIN__USER__MANAGER_VIEW,
        ],
    ],
    Roles::RA__USER__MANAGER_DELETE => [
        'type' => 1,
        'description' => 'РА. Пользователи. Менеджеры. Удаление',
        'children' => [
            Permissions::ADMIN__USER__MANAGER_DELETE,
        ],
    ],
    Roles::RC__LCL__BOOKING => [
        'type' => 1,
        'description' => 'РК. Сборный груз',
        'children' => [
            Permissions::CLIENT__LCL__BOOKING_LIST,
            Permissions::CLIENT__LCL__BOOKING_VIEW,
        ],
    ],
    Roles::RC__ORDER => [
        'type' => 1,
        'description' => 'РК. ЖД перевозки',
        'children' => [
            Permissions::CLIENT__ORDER_CREATE,
            Permissions::CLIENT__ORDER_VIEW,
        ],
    ],
    Roles::RC__ORDERCARDELIVERY => [
        'type' => 1,
        'description' => 'РК. Автодоставка',
        'children' => [
            Permissions::CLIENT__ORDERCARDELIVERY_CREATE,
            Permissions::CLIENT__ORDERCARDELIVERY_VIEW,
        ],
    ],
    Roles::RC__ORDERCUSTOMPROCESSING => [
        'type' => 1,
        'description' => 'РК. Терминальная обработка',
        'children' => [
            Permissions::CLIENT__ORDERCUSTOMPROCESSING_CREATE,
            Permissions::CLIENT__ORDERCUSTOMPROCESSING_UPDATE,
            Permissions::CLIENT__ORDERCUSTOMPROCESSING_VIEW,
        ],
    ],
    Roles::RC__ORDERCUSTOMPROCESSING_CHANGESTATUS => [
        'type' => 1,
        'description' => 'РК. Терминальная обработка. Изменение статуса',
        'children' => [
            Permissions::CLIENT__ORDERCUSTOMPROCESSING_CHANGESTATUS,
        ],
    ],
    Roles::RC__REQUEST => [
        'type' => 1,
        'description' => 'РК. Запросы',
        'children' => [
            Permissions::CLIENT__REQUEST_CREATE,
            Permissions::CLIENT__REQUEST_VIEW,
            Permissions::CLIENT__REQUEST_UPDATE,
        ],
    ],
    Permissions::ADMIN__CONTRACT_LIST => [
        'type' => 2,
        'description' => 'ОА. Контракты',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_CLIENTS,
        ],
    ],
    Permissions::ADMIN__CONTRACT_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Контракты. Обновление',
        'children' => [
            Permissions::ADMIN__CONTRACT_LIST,
        ],
    ],
    Permissions::ADMIN__DASHBOARD => [
        'type' => 2,
        'description' => 'ОА. Доступ к панели администратора',
    ],
    Permissions::ADMIN__DASHBOARD_MENU_SIDE => [
        'type' => 2,
        'description' => 'ОА. Меню. Показывать боковое меню',
        'children' => [
            Permissions::ADMIN__DASHBOARD,
        ],
    ],
    Permissions::ADMIN__DASHBOARD_MENU_TOPNAV => [
        'type' => 2,
        'description' => 'ОА. Меню. Показывать кнопку "Основное меню"',
        'children' => [
            Permissions::ADMIN__DASHBOARD,
        ],
    ],
    Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_CLIENTS => [
        'type' => 2,
        'description' => 'ОА. Меню. Показывать раздел "Клиенты" в "Основном меню"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV,
        ],
    ],
    Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY => [
        'type' => 2,
        'description' => 'ОА. Меню. Показывать раздел "Справочники" в "Основном меню"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Типы контейнеров". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Типы контейнеров". Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Типы контейнеров"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Типы контейнеров". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_VIEW,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_VIEW => [
        'type' => 2,
        'description' => 'ОА. Справочник "Типы контейнеров". Детальный просмотр',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CONTAINERTYPE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__CURRENCY_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Валюты". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CURRENCY_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__CURRENCY_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Валюты"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__CURRENCY_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Валюты". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__CURRENCY_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__GEO__CITY_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "География". Города. Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__GEO__CITY_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__GEO__CITY_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "География". Города',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__GEO__CITY_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "География". Города. Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__GEO__CITY_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "География". Страны. Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "География". Страны. Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "География". Страны',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "География". Страны. Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__GEO__COUNTRY_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__INCOTERM_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Условия Инкотермс". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__INCOTERM_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__INCOTERM_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Условия Инкотермс". Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__INCOTERM_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__INCOTERM_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Условия Инкотермс"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__INCOTERM_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Условия Инкотермс". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__INCOTERM_VIEW,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__INCOTERM_VIEW => [
        'type' => 2,
        'description' => 'ОА. Справочник "Условия Инкотермс". Детальный просмотр',
        'children' => [
            Permissions::ADMIN__DIRECTORY__INCOTERM_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__LCLAGENT_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Агенты сборных грузоперевозок". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__LCLAGENT_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__LCLAGENT_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Агенты сборных грузоперевозок"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__LCLAGENT_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Агенты сборных грузоперевозок". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__LCLAGENT_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__RAILROAD_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "ЖД станции". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__RAILROAD_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__RAILROAD_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "ЖД станции". Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__RAILROAD_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__RAILROAD_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "ЖД станции"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__RAILROAD_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "ЖД станции". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__RAILROAD_VIEW,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__RAILROAD_VIEW => [
        'type' => 2,
        'description' => 'ОА. Справочник "ЖД станции". Детальный просмотр',
        'children' => [
            Permissions::ADMIN__DIRECTORY__RAILROAD_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__ROUTE_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Маршруты". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__ROUTE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__ROUTE_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Маршруты". Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__ROUTE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__ROUTE_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Маршруты"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__ROUTE_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Маршруты". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__ROUTE_VIEW,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__ROUTE_VIEW => [
        'type' => 2,
        'description' => 'ОА. Справочник "Маршруты". Детальный просмотр',
        'children' => [
            Permissions::ADMIN__DIRECTORY__ROUTE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__SERVICE_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Усуги". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__SERVICE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__SERVICE_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Усуги"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__SERVICE_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Усуги". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__SERVICE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__STOCK_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Стоки". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__STOCK_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__STOCK_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Стоки". Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__STOCK_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__STOCK_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Стоки"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__STOCK_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Стоки". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__STOCK_VIEW,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__STOCK_VIEW => [
        'type' => 2,
        'description' => 'ОА. Справочник "Стоки". Детальный просмотр',
        'children' => [
            Permissions::ADMIN__DIRECTORY__STOCK_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TERMINAL_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Терминалы". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TERMINAL_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TERMINAL_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Терминалы". Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TERMINAL_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TERMINAL_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Терминалы"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TERMINAL_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Терминалы". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TERMINAL_VIEW,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TERMINAL_VIEW => [
        'type' => 2,
        'description' => 'ОА. Справочник "Терминалы". Детальный просмотр',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TERMINAL_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSIT_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Погран. переходы". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSIT_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSIT_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Погран. переходы". Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSIT_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSIT_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Погран. переходы"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSIT_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Погран. переходы". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSIT_VIEW,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSIT_VIEW => [
        'type' => 2,
        'description' => 'ОА. Справочник "Погран. переходы". Детальный просмотр',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSIT_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_CREATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Транспортные профили". Создание',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_DELETE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Транспортные профили". Удаление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_LIST,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_LIST => [
        'type' => 2,
        'description' => 'ОА. Справочник "Транспортные профили"',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_DIRECTORY,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Справочник "Транспортные профили". Обновление',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_VIEW,
        ],
    ],
    Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_VIEW => [
        'type' => 2,
        'description' => 'ОА. Справочник "Транспортные профили". Детальный просмотр',
        'children' => [
            Permissions::ADMIN__DIRECTORY__TRANSPORTTYPE_LIST,
        ],
    ],
    Permissions::ADMIN__FINANCE__INVOICE_CREATE => [
        'type' => 2,
        'description' => 'ОА. Финансы. Счета. Создание',
        'children' => [
            Permissions::ADMIN__FINANCE__INVOICE_LIST,
        ],
    ],
    Permissions::ADMIN__FINANCE__INVOICE_DELETE => [
        'type' => 2,
        'description' => 'ОА. Финансы. Счета. Удаление',
        'children' => [
            Permissions::ADMIN__FINANCE__INVOICE_LIST,
        ],
    ],
    Permissions::ADMIN__FINANCE__INVOICE_HISTORY => [
        'type' => 2,
        'description' => 'ОА. Финансы. Счета. История изменений',
        'children' => [
            Permissions::ADMIN__FINANCE__INVOICE_VIEW,
        ],
    ],
    Permissions::ADMIN__FINANCE__INVOICE_LIST => [
        'type' => 2,
        'description' => 'ОА. Финансы. Счета',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__FINANCE__INVOICE_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Финансы. Счета. Обновление',
        'children' => [
            Permissions::ADMIN__FINANCE__INVOICE_VIEW,
        ],
    ],
    Permissions::ADMIN__FINANCE__INVOICE_VIEW => [
        'type' => 2,
        'description' => 'ОА. Финансы. Счета. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__FINANCE__INVOICE_LIST,
        ],
    ],
    Permissions::ADMIN__FINANCE__REPORT_CREATE => [
        'type' => 2,
        'description' => 'ОА. Финансы. Отчеты. Создание',
        'children' => [
            Permissions::ADMIN__FINANCE__REPORT_LIST,
        ],
    ],
    Permissions::ADMIN__FINANCE__REPORT_LIST => [
        'type' => 2,
        'description' => 'ОА. Финансы. Отчеты',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__FINANCE__REPORT_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Финансы. Отчеты. Обновление',
        'children' => [
            Permissions::ADMIN__FINANCE__REPORT_VIEW,
        ],
    ],
    Permissions::ADMIN__FINANCE__REPORT_VIEW => [
        'type' => 2,
        'description' => 'ОА. Финансы. Отчеты. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__FINANCE__REPORT_LIST,
        ],
    ],
    Permissions::ADMIN__LCL__BOOKING_CREATE => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Заказы. Создание',
        'children' => [
            Permissions::ADMIN__LCL__BOOKING_LIST,
        ],
    ],
    Permissions::ADMIN__LCL__BOOKING_DELETE => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Заказы. Удаление',
        'children' => [
            Permissions::ADMIN__LCL__BOOKING_LIST,
        ],
    ],
    Permissions::ADMIN__LCL__BOOKING_HISTORY => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Заказы. История изменений',
        'children' => [
            Permissions::ADMIN__LCL__BOOKING_VIEW,
        ],
    ],
    Permissions::ADMIN__LCL__BOOKING_LIST => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Заказы',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__LCL__BOOKING_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Заказы. Обновление',
        'children' => [
            Permissions::ADMIN__LCL__BOOKING_VIEW,
        ],
    ],
    Permissions::ADMIN__LCL__BOOKING_VIEW => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Заказы. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__LCL__BOOKING_LIST,
        ],
    ],
    Permissions::ADMIN__LCL__CONTAINER_BOOKING => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Контейнеры. Просмотр заказов',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_LIST,
        ],
    ],
    Permissions::ADMIN__LCL__CONTAINER_CREATE => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Контейнеры. Создание',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_LIST,
        ],
    ],
    Permissions::ADMIN__LCL__CONTAINER_DELETE => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Контейнеры. Удаление',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_LIST,
        ],
    ],
    Permissions::ADMIN__LCL__CONTAINER_HISTORY => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Контейнеры. История изменений',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_UPDATE,
        ],
    ],
    Permissions::ADMIN__LCL__CONTAINER_LIST => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Контейнеры',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__LCL__CONTAINER_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Сборный груз. Контейнеры. Обновление',
        'children' => [
            Permissions::ADMIN__LCL__CONTAINER_LIST,
        ],
    ],
    Permissions::ADMIN__ORDER_CHANGESTATUS => [
        'type' => 2,
        'description' => 'ОА. ЖД перевозки. Изменение статуса',
        'children' => [
            Permissions::ADMIN__ORDER_UPDATE,
        ],
    ],
    Permissions::ADMIN__ORDER_LIST => [
        'type' => 2,
        'description' => 'ОА. ЖД перевозки',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__ORDER_UPDATE => [
        'type' => 2,
        'description' => 'ОА. ЖД перевозки. Обновление',
        'children' => [
            Permissions::ADMIN__ORDER_VIEW,
        ],
    ],
    Permissions::ADMIN__ORDER_UPDATETRIP => [
        'type' => 2,
        'description' => 'ОА. ЖД перевозки. Обновление маршрута',
        'children' => [
            Permissions::ADMIN__ORDER_UPDATE,
        ],
    ],
    Permissions::ADMIN__ORDER_VIEW => [
        'type' => 2,
        'description' => 'ОА. ЖД перевозки. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__ORDER_LIST,
        ],
    ],
    Permissions::ADMIN__ORDERCARDELIVERY_CHANGECONTAINERCODE => [
        'type' => 2,
        'description' => 'ОА. Автодоставка. Изменение номера контейнера',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_UPDATE,
        ],
    ],
    Permissions::ADMIN__ORDERCARDELIVERY_CHANGESTATUS => [
        'type' => 2,
        'description' => 'ОА. Автодоставка. Изменение статуса',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_UPDATE,
        ],
    ],
    Permissions::ADMIN__ORDERCARDELIVERY_CREATE => [
        'type' => 2,
        'description' => 'ОА. Автодоставка. Создание',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_LIST,
        ],
    ],
    Permissions::ADMIN__ORDERCARDELIVERY_DELETE => [
        'type' => 2,
        'description' => 'ОА. Автодоставка. Удаление',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_LIST,
        ],
    ],
    Permissions::ADMIN__ORDERCARDELIVERY_HISTORY => [
        'type' => 2,
        'description' => 'ОА. Автодоставка. История изменений',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_VIEW,
        ],
    ],
    Permissions::ADMIN__ORDERCARDELIVERY_LIST => [
        'type' => 2,
        'description' => 'ОА. Автодоставка',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__ORDERCARDELIVERY_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Автодоставка. Обновление',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_VIEW,
        ],
    ],
    Permissions::ADMIN__ORDERCARDELIVERY_VIEW => [
        'type' => 2,
        'description' => 'ОА. Автодоставка. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__ORDERCARDELIVERY_LIST,
        ],
        'ruleName' => 'checkManagerOrganizationLimits',
    ],
    Permissions::ADMIN__ORDERCUSTOMPROCESSING_CHANGESTATUS => [
        'type' => 2,
        'description' => 'ОА. Терминальная обработка. Изменение статуса',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_UPDATE,
        ],
    ],
    Permissions::ADMIN__ORDERCUSTOMPROCESSING_CREATE => [
        'type' => 2,
        'description' => 'ОА. Терминальная обработка. Создание',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_LIST,
        ],
    ],
    Permissions::ADMIN__ORDERCUSTOMPROCESSING_DELETE => [
        'type' => 2,
        'description' => 'ОА. Терминальная обработка. Удаление',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_LIST,
        ],
    ],
    Permissions::ADMIN__ORDERCUSTOMPROCESSING_LIST => [
        'type' => 2,
        'description' => 'ОА. Терминальная обработка',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__ORDERCUSTOMPROCESSING_TRANSFERTRIP => [
        'type' => 2,
        'description' => 'ОА. Терминальная обработка. Маршрут',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_UPDATE,
        ],
    ],
    Permissions::ADMIN__ORDERCUSTOMPROCESSING_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Терминальная обработка. Обновление',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_VIEW,
        ],
    ],
    Permissions::ADMIN__ORDERCUSTOMPROCESSING_VIEW => [
        'type' => 2,
        'description' => 'ОА. Терминальная обработка. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__ORDERCUSTOMPROCESSING_LIST,
        ],
    ],
    Permissions::ADMIN__ORGANIZATION_CREATE => [
        'type' => 2,
        'description' => 'ОА. Организации. Создание',
        'children' => [
            Permissions::ADMIN__ORGANIZATION_LIST,
        ],
    ],
    Permissions::ADMIN__ORGANIZATION_DELETE => [
        'type' => 2,
        'description' => 'ОА. Организации. Удаление',
        'children' => [
            Permissions::ADMIN__ORGANIZATION_LIST,
        ],
    ],
    Permissions::ADMIN__ORGANIZATION_LIST => [
        'type' => 2,
        'description' => 'ОА. Организации',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_CLIENTS,
        ],
    ],
    Permissions::ADMIN__ORGANIZATION_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Организации. Обновление',
        'children' => [
            Permissions::ADMIN__ORGANIZATION_VIEW,
        ],
    ],
    Permissions::ADMIN__ORGANIZATION_VIEW => [
        'type' => 2,
        'description' => 'ОА. Организации. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__ORGANIZATION_LIST,
        ],
    ],
    Permissions::ADMIN__REQUEST_CHANGESTATUS => [
        'type' => 2,
        'description' => 'ОА. Запросы. Изменение статуса',
        'children' => [
            Permissions::ADMIN__REQUEST_UPDATE,
        ],
    ],
    Permissions::ADMIN__REQUEST_CREATE => [
        'type' => 2,
        'description' => 'ОА. Запросы. Создание',
        'children' => [
            Permissions::ADMIN__REQUEST_LIST,
        ],
    ],
    Permissions::ADMIN__REQUEST_DELETE => [
        'type' => 2,
        'description' => 'ОА. Запросы. Удаление',
        'children' => [
            Permissions::ADMIN__REQUEST_LIST,
        ],
    ],
    Permissions::ADMIN__REQUEST_DELETE_RATES => [
        'type' => 2,
        'descriptions' => 'ОА. Запросы. Удаление рейтов',
        'ruleName' => 'checkMainPerson',
    ],
    Permissions::ADMIN__REQUEST_HISTORY => [
        'type' => 2,
        'description' => 'ОА. Запросы. История изменений',
        'children' => [
            Permissions::ADMIN__REQUEST_VIEW,
        ],
    ],
    Permissions::ADMIN__REQUEST_LIST => [
        'type' => 2,
        'description' => 'ОА. Запросы',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__REQUEST_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Запросы. Обновление',
        'children' => [
            Permissions::ADMIN__REQUEST_VIEW,
            Permissions::ADMIN__REQUEST__TAKE,
        ],
    ],
    Permissions::ADMIN__REQUEST_VIEW => [
        'type' => 2,
        'description' => 'ОА. Запросы. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__REQUEST_LIST,
        ],
    ],
    Permissions::ADMIN__REQUEST__TAKE => [
        'type' => 2,
        'description' => 'ОА. Запросы. Назначение себя ответственным менеджером',
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_CHANGE_STATUS => [
        'type' => 2,
        'description' => 'ОА. Заявки на перевозку. Изменение статуса',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_UPDATE,
        ],
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_CREATE => [
        'type' => 2,
        'description' => 'ОА. Заявки на перевозку. Создание',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_LIST,
        ],
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_DELETE => [
        'type' => 2,
        'description' => 'ОА. Заявки на перевозку. Удаление',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_LIST,
        ],
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_DELETE_RATES => [
        'type' => 2,
        'descriptions' => 'ОА. Заявки на перевозку. Удаление рейтов',
        'ruleName' => 'checkMainPerson',
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_HISTORY => [
        'type' => 2,
        'description' => 'ОА. Заявки на перевозку. История изменений',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_VIEW,
        ],
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_LIST => [
        'type' => 2,
        'description' => 'ОА. Заявки на перевозку',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Заявки на перевозку. Обновление',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_VIEW,
            Permissions::ADMIN__TRANSPORTATION_ORDER_TAKE,
        ],
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_VIEW => [
        'type' => 2,
        'description' => 'ОА. Заявки на перевозку. Детальный просмотр',
        'children' => [
            Permissions::ADMIN__TRANSPORTATION_ORDER_LIST,
        ],
    ],
    Permissions::ADMIN__TRANSPORTATION_ORDER_TAKE => [
        'type' => 2,
        'description' => 'ОА. Заявки на перевозку. Назначение себя ответственным менеджером',
    ],
    Permissions::ADMIN__LOGISTICS__LIST => [
        'type' => 2,
        'description' => 'ОА. Кабинет логиста. Просмотр запросов',
    ],
    Permissions::ADMIN__LOGISTICS__DELETE_RATES => [
        'type' => 2,
        'description' => 'ОА. Кабинет логиста. Удаление рейтов',
        'ruleName' => 'checkMainPerson',
    ],
    Permissions::ADMIN__LOGISTICS__UPDATE_RATES => [
        'type' => 2,
        'description' => 'ОА. Кабинет логиста. Изменение ставок',
    ],
    Permissions::ADMIN__LOGISTICS__TAKE => [
        'type' => 2,
        'description' => 'ОА. Кабинет логиста. Назначение себя ответственным логистом',
    ],
    Permissions::ADMIN__LOGISTICS__CHANGESTATUS => [
        'type' => 2,
        'description' => 'ОА. Кабинет логиста. Изменение статуса',
    ],
    Permissions::ADMIN__USER__CLIENT_CREATE => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Клиенты. Добавление',
        'children' => [
            Permissions::ADMIN__USER__CLIENT_LIST,
        ],
    ],
    Permissions::ADMIN__USER__CLIENT_DELETE => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Клиенты. Удаление',
        'ruleName' => 'checkUserType',
        'data' => [
            'roleControl' => 'Client'
        ],
        'children' => [
            Permissions::ADMIN__USER__CLIENT_LIST,
        ],
    ],
    Permissions::ADMIN__USER__CLIENT_LIST => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Клиенты',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_CLIENTS,
        ],
    ],
    Permissions::ADMIN__USER__CLIENT_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Клиенты. Обновление',
        'ruleName' => 'checkUserType',
        'data' => [
            'roleControl' => 'Client'
        ],
        'children' => [
            Permissions::ADMIN__USER__CLIENT_VIEW,
        ],
    ],
    Permissions::ADMIN__USER__CLIENT_VIEW => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Клиенты. Детальный просмотр',
        'ruleName' => 'checkUserType',
        'data' => [
            'roleControl' => 'Client'
        ],
        'children' => [
            Permissions::ADMIN__USER__CLIENT_LIST,
        ],
    ],
    Permissions::ADMIN__USER__MANAGER_CREATE => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Менеджеры. Добавление',
        'children' => [
            Permissions::ADMIN__USER__MANAGER_LIST,
        ],
    ],
    Permissions::ADMIN__USER__MANAGER_DELETE => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Менеджеры. Удаление',
        'ruleName' => 'checkUserType',
        'data' => [
            'roleControl' => 'Manager'
        ],
        'children' => [
            Permissions::ADMIN__USER__MANAGER_LIST,
        ],
    ],
    Permissions::ADMIN__USER__MANAGER_LIST => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Менеджеры',
        'children' => [
            Permissions::ADMIN__DASHBOARD_MENU_TOPNAV_CLIENTS,
        ],
    ],
    Permissions::ADMIN__USER__MANAGER_UPDATE => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Менеджеры. Обновление',
        'ruleName' => 'checkUserType',
        'data' => [
            'roleControl' => 'Manager'
        ],
        'children' => [
            Permissions::ADMIN__USER__MANAGER_VIEW,
        ],
    ],
    Permissions::ADMIN__USER__MANAGER_VIEW => [
        'type' => 2,
        'description' => 'ОА. Пользователи. Менеджеры. Детальный просмотр',
        'ruleName' => 'checkUserType',
        'data' => [
            'roleControl' => 'Manager'
        ],
        'children' => [
            Permissions::ADMIN__USER__MANAGER_LIST,
        ],
    ],
    Permissions::CLIENT__DASHBOARD => [
        'type' => 2,
        'description' => 'ОК. Доступ к панели клиента',
    ],
    Permissions::CLIENT__DASHBOARD_MENU_SIDE => [
        'type' => 2,
        'description' => 'ОК. Меню. Показывать боковое меню',
        'children' => [
            Permissions::CLIENT__DASHBOARD,
        ],
    ],
    Permissions::CLIENT__LCL__BOOKING_LIST => [
        'type' => 2,
        'description' => 'ОК. Сборный груз. Заказы',
    ],
    Permissions::CLIENT__LCL__BOOKING_VIEW => [
        'type' => 2,
        'description' => 'ОК. Сборный груз. Заказы. Детальный просмотр',
    ],
    Permissions::CLIENT__ORDER_CREATE => [
        'type' => 2,
        'description' => 'ОК. ЖД перевозки. Создание',
        'children' => [
            Permissions::CLIENT__ORDER_LIST,
        ],
    ],
    Permissions::CLIENT__ORDER_LIST => [
        'type' => 2,
        'description' => 'ОК. ЖД перевозки',
        'children' => [
            Permissions::CLIENT__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::CLIENT__ORDER_VIEW => [
        'type' => 2,
        'description' => 'ОК. ЖД перевозки. Детальный просмотр',
        'ruleName' => 'checkMain',
        'children' => [
            Permissions::CLIENT__ORDER_LIST,
        ],
    ],
    Permissions::CLIENT__ORDERCARDELIVERY_CREATE => [
        'type' => 2,
        'description' => 'ОК. Автодоставка. Создание',
        'children' => [
            Permissions::CLIENT__ORDERCARDELIVERY_LIST,
        ],
    ],
    Permissions::CLIENT__ORDERCARDELIVERY_LIST => [
        'type' => 2,
        'description' => 'ОК. Автодоставка',
        'children' => [
            Permissions::CLIENT__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::CLIENT__ORDERCARDELIVERY_VIEW => [
        'type' => 2,
        'description' => 'ОК. Автодоставка. Детальный просмотр',
        'ruleName' => 'checkMain',
        'children' => [
            Permissions::CLIENT__ORDERCARDELIVERY_LIST,
        ],
    ],
    Permissions::CLIENT__ORDERCUSTOMPROCESSING_CHANGESTATUS => [
        'type' => 2,
        'description' => 'ОК. Терминальная обработка. Изменение статуса',
        'ruleName' => 'checkMain',
        'children' => [
            Permissions::CLIENT__ORDERCUSTOMPROCESSING_UPDATE,
        ],
    ],
    Permissions::CLIENT__ORDERCUSTOMPROCESSING_CREATE => [
        'type' => 2,
        'description' => 'ОК. Терминалная обработка. Создание',
        'children' => [
            Permissions::CLIENT__ORDERCUSTOMPROCESSING_LIST,
        ],
    ],
    Permissions::CLIENT__ORDERCUSTOMPROCESSING_LIST => [
        'type' => 2,
        'description' => 'ОК. Терминальная обработка',
        'children' => [
            Permissions::CLIENT__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::CLIENT__ORDERCUSTOMPROCESSING_UPDATE => [
        'type' => 2,
        'description' => 'ОК. Терминальная обработка. Обновление',
        'ruleName' => 'checkMain',
        'children' => [
            Permissions::CLIENT__ORDERCUSTOMPROCESSING_VIEW,
        ],
    ],
    Permissions::CLIENT__ORDERCUSTOMPROCESSING_VIEW => [
        'type' => 2,
        'description' => 'ОК. Терминальная обработка. Детальный просмотр',
        'ruleName' => 'checkMain',
        'children' => [
            Permissions::CLIENT__ORDERCUSTOMPROCESSING_LIST,
        ],
    ],
    Permissions::CLIENT__REQUEST_CREATE => [
        'type' => 2,
        'description' => 'ОК. Запросы. Создание',
        'children' => [
            Permissions::CLIENT__REQUEST_LIST,
        ],
    ],
    Permissions::CLIENT__REQUEST_LIST => [
        'type' => 2,
        'description' => 'ОК. Запросы',
        'children' => [
            Permissions::CLIENT__DASHBOARD_MENU_SIDE,
        ],
    ],
    Permissions::CLIENT__REQUEST_VIEW => [
        'type' => 2,
        'description' => 'ОК. Запросы. Детальный просмотр',
        'ruleName' => 'checkMain',
        'children' => [
            Permissions::CLIENT__REQUEST_LIST,
        ],
    ],
    Permissions::CLIENT__REQUEST_UPDATE => [
        'type' => 2,
        'description' => 'ОК. Запросы. Изменение.',
        'ruleName' => 'requestUpdate',
        'children' => [
            Permissions::CLIENT__REQUEST_LIST,
            Permissions::CLIENT__REQUEST_VIEW,
        ],
    ],
];
