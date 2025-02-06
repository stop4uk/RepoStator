<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\widgets\Menu;
use app\modules\users\components\rbac\items\Permissions;

/**
 * @var \yii\web\View $this
 */

$parseItemGroups = in_array($this->context->id, ['groups/default', 'groups/type']);
$parseItemQueue = in_array($this->context->id, ['queue/default', 'queue/template']);
$menuArray = [
    ['label' => Html::tag('i', '', ['class' => 'bi bi-house-fill']) . Yii::t('views', 'Главная'), 'url' => Url::to(['/']), ],


    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-database-fill']) . Yii::t('views', 'Передать отчет'),
        'url' => Url::to(['/send']),
        'visible' => Yii::$app->getUser()->can(Permissions::DATA_SEND)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-ui-checks']) . Yii::t('views', 'Контроль передачи'),
        'url' => Url::to(['/control']),
        'visible' => Yii::$app->getUser()->can(Permissions::DATA_LIST)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-file-earmark-spreadsheet-fill']) . Yii::t('views', 'Статистика'),
        'url' => Url::to(['/statistic']),
        'visible' => Yii::$app->getUser()->can(Permissions::STATISTIC)
    ],


    [
        'label' => Yii::t('views', 'Настройка отчетов'), 'options' => ['class' => 'sidebar-header'],
        'visible' => Yii::$app->getUser()->can(Permissions::REPORT_INCLUDES)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-list-columns-reverse']) . Yii::t('views', 'Список'),
        'url' => Url::to(['/reports']),
        'visible' => Yii::$app->getUser()->can(Permissions::REPORT_LIST)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-infinity']) . Yii::t('views', 'Константы'),
        'url' => Url::to(['/reports/constant']),
        'visible' => Yii::$app->getUser()->can(Permissions::CONSTANT_LIST)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-diagram-3']) . Yii::t('views', 'Структуры предачи'),
        'url' => Url::to(['/reports/structure']),
        'visible' => Yii::$app->getUser()->can(Permissions::STRUCTURE_LIST)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-calculator-fill']) . Yii::t('views', 'Правила сложения'),
        'url' => Url::to(['/reports/constantrule']),
        'visible' => Yii::$app->getUser()->can(Permissions::CONSTANTRULE_LIST)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-box-fill']) . Yii::t('views', 'Шаблоны формирования'),
        'url' => Url::to(['/reports/template']),
        'visible' => Yii::$app->getUser()->can(Permissions::TEMPLATE_LIST)
    ],


    [
        'label' => Yii::t('views', 'Администрирование'), 'options' => ['class' => 'sidebar-header'],
        'visible' => Yii::$app->getUser()->can(Permissions::ADMIN_INCLUDES)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-people-fill']) . Yii::t('views', 'Пользователи'),
        'url' => Url::to(['/admin/users']),
        'visible' => Yii::$app->getUser()->can(Permissions::ADMIN_USER_LIST)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-collection-fill']) . Html::tag('span', Yii::t('views', 'Группы'), ['class' => 'align-middle']),
        'url' => '#',
        'template' => '<a class="sidebar-link ' . ($parseItemGroups ? '' : 'collapsed'). '" data-bs-toggle="collapse" data-bs-target="#groupsItems">{label}</a>',
        'submenuTemplate' => '<ul id="groupsItems" class="sidebar-dropdown list-unstyled collapse ' . ($parseItemGroups ? 'show' : ''). '" data-bs-parent="#sidebar">{items}</ul>',
        'visible' => (
            Yii::$app->getUser()->can(Permissions::ADMIN_GROUP)
            || Yii::$app->getUser()->can(Permissions::ADMIN_GROUPTYPE)
        ),
        'items' => [
            [
                'label' => Yii::t('views', 'Список'),
                'url' => Url::to(['/admin/groups']),
                'visible' => Yii::$app->getUser()->can(Permissions::ADMIN_GROUP)
            ],
            [
                'label' => Yii::t('views', 'Типы групп'),
                'url' => Url::to(['/admin/groups/type']),
                'visible' => Yii::$app->getUser()->can(Permissions::ADMIN_GROUPTYPE)
            ],
        ],
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-person-raised-hand']) . Html::tag('span', Yii::t('views', 'Очереди'), ['class' => 'align-middle']),
        'url' => '#',
        'template' => '<a class="sidebar-link ' . ($parseItemQueue ? '' : 'collapsed'). '" data-bs-toggle="collapse" data-bs-target="#logsItems">{label}</a>',
        'submenuTemplate' => '<ul id="logsItems" class="sidebar-dropdown list-unstyled collapse ' . ($parseItemQueue ? 'show' : ''). '" data-bs-parent="#sidebar">{items}</ul>',
        'visible' => (
            Yii::$app->getUser()->can(Permissions::ADMIN_QUEUE_SYSTEM)
            || Yii::$app->getUser()->can(Permissions::ADMIN_QUEUE_TEMPLATE_LIST)
        ),
        'items' => [
            [
                'label' => Yii::t('views', 'Общая'),
                'url' => Url::to(['/admin/queue']),
                'visible' => Yii::$app->getUser()->can(Permissions::ADMIN_QUEUE_SYSTEM)
            ],
            [
                'label' => Yii::t('views', 'Формирование отчетов'),
                'url' => Url::to(['/admin/queue/template']),
                'visible' => Yii::$app->getUser()->can(Permissions::ADMIN_QUEUE_TEMPLATE_LIST)
            ],
        ],
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-clock-history']) . Yii::t('views', 'Логи'),
        'url' => Url::to(['/admin/logs']),
        'visible' => Yii::$app->getUser()->can(Permissions::ADMIN_LOG)
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-gear-fill']) . Yii::t('views', 'Настройки системы'),
        'url' => Url::to(['/admin/settings']),
        'visible' => Yii::$app->getUser()->can(Permissions::ADMIN_SETTING)
    ],
];

?>
<nav id="sidebar" class="sidebar js-sidebar">
    <div class="sidebar-content js-simplebar">
        <?php
        echo Html::a(
            Html::tag('span', Yii::$app->settings->get('system', 'app_name'), ['class' => 'align-middle']),
            ['/'],
            ['class' => 'sidebar-brand text-center']
        );

        echo Menu::widget([
            'encodeLabels' => false,
            'options' => ['class' => 'sidebar-nav'],
            'itemOptions' => ['class' => 'sidebar-item'],
            'linkTemplate' => '<a class="sidebar-link" href="{url}">{label}</a>',
            'items' => $menuArray
        ]);
        ?>
    </div>
</nav>