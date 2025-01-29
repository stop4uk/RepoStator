<?php

namespace app\modules\users\components\rbac\items;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\components\rbac\items
 */
final class Roles
{
    const ADMINISTRATOR = '__role_administrator';
    const MANAGER = '__role_manager';
    const LOGIST = '__role_logist';
    const CLIENT = '__role_client';
    const CLIENT_MANAGER = '__role_client_manager';

    const RA__CONTRACT = "_role_admin__contract";
    const RA__DIR__CONTAINERTYPE = "_role_admin__dir__containertype";
    const RA__DIR__CONTAINERTYPE_DELETE = "_role_admin__dir__containertype_delete";
    const RA__DIR__CURRENCY = "_role_admin__dir__currency";
    const RA__DIR__GEO__CITY = "_role_admin__dir__geo__city";
    const RA__DIR__GEO__COUNTRY = "_role_admin__dir__geo__country";
    const RA__DIR__GEO__COUNTRY_DELETE = "_role_admin__dir__geo__country_delete";
    const RA__DIR__INCOTERM = "_role_admin__dir__incoterm";
    const RA__DIR__INCOTERM_DELETE = "_role_admin__dir__incoterm_delete";
    const RA__DIR__LCLAGENT = "_role_admin__dir__lclagent";
    const RA__DIR__RAILROAD = "_role_admin__dir__railroad";
    const RA__DIR__RAILROAD_DELETE = "_role_admin__dir__railroad_delete";
    const RA__DIR__ROUTE = "_role_admin__dir__route";
    const RA__DIR__ROUTE_DELETE = "_role_admin__dir__route_delete";
    const RA__DIR__SERVICE = "_role_admin__dir__service";
    const RA__DIR__STOCK = "_role_admin__dir__stock";
    const RA__DIR__STOCK_DELETE = "_role_admin__dir__stock_delete";
    const RA__DIR__TERMINAL = "_role_admin__dir__terminal";
    const RA__DIR__TERMINAL_DELETE = "_role_admin__dir__terminal_delete";
    const RA__DIR__TRANSIT = "_role_admin__dir__transit";
    const RA__DIR__TRANSIT_DELETE = "_role_admin__dir__transit_delete";
    const RA__DIR__TRANSPORTTYPE = "_role_admin__dir__transporttype";
    const RA__DIR__TRANSPORTTYPE_DELETE = "_role_admin__dir__transporttype_delete";
    const RA__FINANCE__INVOICE = "_role_admin__finance__invoice";
    const RA__FINANCE__INVOICE_DELETE = "_role_admin__finance__invoice_delete";
    const RA__FINANCE__INVOICE_HISTORY = "_role_admin__finance__invoice_history";
    const RA__FINANCE__REPORT = "_role_admin__finance__report";
    const RA__LCL__BOOKING = "_role_admin__lcl__booking";
    const RA__LCL__BOOKING_DELETE = "_role_admin__lcl__booking_delete";
    const RA__LCL__BOOKING_HISTORY = "_role_admin__lcl__booking_history";
    const RA__LCL__CONTAINER = "_role_admin__lcl__container";
    const RA__LCL__CONTAINER_BOOKING = "_role_admin__lcl__container_booking";
    const RA__LCL__CONTAINER_DELETE = "_role_admin__lcl__container_delete";
    const RA__LCL__CONTAINER_HISTORY = "_role_admin__lcl__container_history";
    const RA__LOGISTICS__LIST = "_role_admin__logistics__list";
    const RA__ORDER = "_role_admin__order";
    const RA__ORDER_CHANGESTATUS = "_role_admin__order_changestatus";
    const RA__ORDER_UPDATETRIP = "_role_admin__order_updatetrip";
    const RA__ORDERCARDELIVERY = "_role_admin__ordercardelivery";
    const RA__ORDERCARDELIVERY_CHANGECONTAINERCODE = "_role_admin__ordercardelivery_changecontainercode";
    const RA__ORDERCARDELIVERY_CHANGESTATUS = "_role_admin__ordercardelivery_changestatus";
    const RA__ORDERCARDELIVERY_DELETE = "_role_admin__ordercardelivery_delete";
    const RA__ORDERCARDELIVERY_HISTORY = "_role_admin__ordercardelivery_history";
    const RA__ORDERCUSTOMPROCESSING = "_role_admin__ordercustomprocessing";
    const RA__ORDERCUSTOMPROCESSING_CHANGESTATUS = "_role_admin__ordercustomprocessing_changestatus";
    const RA__ORDERCUSTOMPROCESSING_DELETE = "_role_admin__ordercustomprocessing_delete";
    const RA__ORGANIZATION = "_role_admin__organization";
    const RA__ORGANIZATION_DELETE = "_role_admin__organization_delete";
    const RA__REQUEST = "_role_admin__request";
    const RA__REQUEST_CHANGESTATUS = "_role_admin__request_changestatus";
    const RA__REQUEST_DELETE = "_role_admin__request_delete";
    const RA__REQUEST_HISTORY = "_role_admin__request_history";
    const RA__USER__CLIENT = "_role_admin__user__client";
    const RA__USER__CLIENT_DELETE = "_role_admin__user__client_delete";
    const RA__USER__MANAGER = "_role_admin__user__manager";
    const RA__USER__MANAGER_DELETE = "_role_admin__user__manager_delete";
    const RA__TRANSPORTATION_ORDER = "_role_admin__transportation_order";
    const RA__TRANSPORTATION_ORDER_CHANGESTATUS = "_role_admin__transportation_order_changestatus";
    const RA__TRANSPORTATION_ORDER_DELETE = "_role_admin__transportation_order_delete";
    const RA__TRANSPORTATION_ORDER_HISTORY = "_role_admin__transportation_order_history";
    const RA__TRANSPORTATION_ORDER_UPDATE_CALENDAR = "_role_admin__transportation_order_update_calendar";
    const RC__LCL__BOOKING = "_role_client__lcl__booking";
    const RC__ORDER = "_role_client__order";
    const RC__ORDERCARDELIVERY = "_role_client__ordercardelivery";
    const RC__ORDERCUSTOMPROCESSING = "_role_client__ordercustomprocessing";
    const RC__ORDERCUSTOMPROCESSING_CHANGESTATUS = "_role_client__ordercustomprocessing_changestatus";
    const RC__REQUEST = "_role_client__request";
    const RC__TRANSPORTATION_ORDER = "_role_client__transportation_order";
}