<?php

/**
 * @var \yii\web\View $this
 */

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\widgets\Menu;

$parseItemGroups = in_array($this->context->id, ['admin/groups/default', 'admin/groups/type']);
$parseItemQueue = in_array($this->context->id, ['admin/queue/default', 'admin/queue/template']);
$menuArray = [
    ['label' => Html::tag('i', '', ['class' => 'bi bi-house-fill']) . Yii::t('views', 'Главная'), 'url' => Url::to(['/']), ],


    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-database-fill']) . Yii::t('views', 'Передать отчет'),
        'url' => Url::to(['/send']),
        'visible' => Yii::$app->getUser()->can('data.send')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-ui-checks']) . Yii::t('views', 'Контроль передачи'),
        'url' => Url::to(['/control']),
        'visible' => Yii::$app->getUser()->can('data.list')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-file-earmark-spreadsheet-fill']) . Yii::t('views', 'Статистика'),
        'url' => Url::to(['/statistic']),
        'visible' => Yii::$app->getUser()->can('statistic')
    ],


    [
        'label' => Yii::t('views', 'Настройка отчетов'), 'options' => ['class' => 'sidebar-header'],
        'visible' => Yii::$app->getUser()->can('report.includes')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-list-columns-reverse']) . Yii::t('views', 'Список'),
        'url' => Url::to(['/reports']),
        'visible' => Yii::$app->getUser()->can('report.list')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-infinity']) . Yii::t('views', 'Константы'),
        'url' => Url::to(['/reports/constant']),
        'visible' => Yii::$app->getUser()->can('constant.list')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-diagram-3']) . Yii::t('views', 'Структуры предачи'),
        'url' => Url::to(['/reports/structure']),
        'visible' => Yii::$app->getUser()->can('structure.list')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-calculator-fill']) . Yii::t('views', 'Правила сложения'),
        'url' => Url::to(['/reports/constantrule']),
        'visible' => Yii::$app->getUser()->can('constantRule.list')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-box-fill']) . Yii::t('views', 'Шаблоны формирования'),
        'url' => Url::to(['/reports/template']),
        'visible' => Yii::$app->getUser()->can('template.list')
    ],


    [
        'label' => Yii::t('views', 'Администрирование'), 'options' => ['class' => 'sidebar-header'],
        'visible' => Yii::$app->getUser()->can('admin.includes')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-people-fill']) . Yii::t('views', 'Пользователи'),
        'url' => Url::to(['/admin/users']),
        'visible' => Yii::$app->getUser()->can('admin.user.list')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-collection-fill']) . Html::tag('span', Yii::t('views', 'Группы'), ['class' => 'align-middle']),
        'url' => '#',
        'template' => '<a class="sidebar-link ' . ( $parseItemGroups ? '' : 'collapsed'). '" data-bs-toggle="collapse" data-bs-target="#groupsItems">{label}</a>',
        'submenuTemplate' => '<ul id="groupsItems" class="sidebar-dropdown list-unstyled collapse ' . ($parseItemGroups ? 'show' : ''). '" data-bs-parent="#sidebar">{items}</ul>',
        'visible' => (
            Yii::$app->getUser()->can('admin.group')
            || Yii::$app->getUser()->can('admin.groupType')
        ),
        'items' => [
            [
                'label' => Yii::t('views', 'Список'),
                'url' => Url::to(['/admin/groups']),
                'visible' => Yii::$app->getUser()->can('admin.group')
            ],
            [
                'label' => Yii::t('views', 'Типы групп'),
                'url' => Url::to(['/admin/groups/type']),
                'visible' => Yii::$app->getUser()->can('admin.groupType')
            ],
        ],
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-person-raised-hand']) . Html::tag('span', Yii::t('views', 'Очереди'), ['class' => 'align-middle']),
        'url' => '#',
        'template' => '<a class="sidebar-link ' . ( $parseItemQueue ? '' : 'collapsed'). '" data-bs-toggle="collapse" data-bs-target="#logsItems">{label}</a>',
        'submenuTemplate' => '<ul id="logsItems" class="sidebar-dropdown list-unstyled collapse ' . ($parseItemQueue ? 'show' : ''). '" data-bs-parent="#sidebar">{items}</ul>',
        'visible' => Yii::$app->getUser()->can('admin.queue'),
        'items' => [
            [
                'label' => Yii::t('views', 'Общая'),
                'url' => Url::to(['/admin/queue']),
                'visible' => Yii::$app->getUser()->can('admin.queue.system')
            ],
            [
                'label' => Yii::t('views', 'Формирование отчетов'),
                'url' => Url::to(['/admin/queue/template']),
                'visible' => Yii::$app->getUser()->can('admin.queue.template.list')
            ],
        ],
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-clock-history']) . Yii::t('views', 'Логи'),
        'url' => Url::to(['/admin/logs']),
        'visible' => Yii::$app->getUser()->can('admin.log')
    ],
    [
        'label' => Html::tag('i', '', ['class' => 'bi bi-gear-fill']) . Yii::t('views', 'Настройки системы'),
        'url' => Url::to(['/admin/settings']),
        'visible' => Yii::$app->getUser()->can('admin.setting')
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