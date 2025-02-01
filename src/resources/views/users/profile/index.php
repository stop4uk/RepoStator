<?php

use yii\helpers\{
    Url,
    ArrayHelper
};
use yii\widgets\Pjax;
use yii\grid\ActionColumn;
use yii\bootstrap5\{
    Html,
    Modal
};

use app\widgets\GridView;

/**
 * @var \yii\web\View $this
 * @var \app\modules\users\models\user\ProfileModel $model
 * @var \app\modules\users\forms\user\UserEmailChangeForm $userEmailChangeForm
 * @var \app\modules\users\forms\user\UserPasswordChangeForm $userPasswordChangeForm
 * @var array $emailchangesDataProvider
 */

$this->title = Yii::t('views', 'Профиль');

?>

<div class="row">
    <div class="d-none d-md-block col-md-4 col-xxl-3">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="h6 card-title d-inline">
                    <?= Yii::t('views', 'Последний сеанс'); ?>
                </h5>
                <?php
                    Modal::begin([
                        'size' => Modal::SIZE_LARGE,
                        'title' => Yii::t('views', 'Последние 20 авторизаций'),
                        'toggleButton' => [
                            'label' => Yii::t('views', 'История'),
                            'class' => 'badge bg-dark border-0 float-end'
                        ],
                    ]);
                        echo $this->renderAjax('_partial/authHistory', [
                            'data' => $model->getEntity()->sessions
                        ]);
                    Modal::end();
                ?>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-1">
                        <span class="bi bi-alarm me-1"></span>
                        <?= Yii::t('views', 'Время: {time}', [
                            'time' => Yii::$app->formatter->asDatetime($model->getEntity()->lastAuth?->created_at)
                        ]); ?>
                    </li>
                    <li class="mb-1">
                        <span class="bi bi-ethernet me-1"></span>
                        <?= Yii::t('views', 'IP: {ip}', ['ip' => $model->getEntity()->lastAuth?->ip]); ?>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><?= Yii::t('views', 'Привязанная группа'); ?></h5>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <?php if (Yii::$app->getUser()->getIdentity()->group) {
                    echo Yii::$app->getUser()->getIdentity()->groups[Yii::$app->getUser()->getIdentity()->group];
                } ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><?= Yii::t('views', 'Роли'); ?></h5>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <?php if ($model->getEntity()->rights) {
                    $rightsItemsName = ArrayHelper::map(Yii::$app->getAuthManager()->getRoles(), 'name', 'description');

                    foreach ($model->getEntity()->rights as $right) {
                        echo Html::tag('span', $rightsItemsName[$right->item_name], ['class' => 'badge bg-success me-2']);
                    }
                } ?>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-8 col-xxl-9">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0 text-center text-md-start">
                    <?= Yii::t('views', 'Общие данные # {email}', ['email' => $model->getEntity()->email]) ?>
                </h5>
            </div>
            <div class="card-body h-100">
                <?= $this->render('_partial/form', [
                    'model' => $model,
                    'userEmailChangeForm' => $userEmailChangeForm,
                    'userPasswordChangeForm' => $userPasswordChangeForm,
                ]); ?>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <?= Yii::t('views', 'Заявки на смену Email') ?>
                </h5>
            </div>
            <div class="card-body">
                <?php
                    Pjax::begin(['id' => 'emailchangesList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]);
                        echo GridView::widget([
                            'dataProvider' => $emailchangesDataProvider,
                            'emptyText' => Yii::t('views', 'Активные заявки отсутствуют'),
                            'tableOptions' => ['class' => 'table'],
                            'columns' => [
                                [
                                    'attribute' => 'created_at',
                                    'headerOptions' => ['style' => 'min-width: 8rem'],
                                    'label' => Yii::t('entities', 'Создана'),
                                    'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')]
                                ],
                                'email',
                                [
                                    'class' => ActionColumn::class,
                                    'header' => false,
                                    'headerOptions' => ['style' => 'min-width:12rem'],
                                    'contentOptions' => ['class' => 'text-center'],
                                    'template' => '{cancel}',
                                    'buttons' => [
                                        'cancel' => function($url, $model) {
                                            return Html::tag('span',
                                                Html::tag('i', '', ['class' => 'bi bi-trash text-dark']),
                                                [
                                                    'role' => 'button',
                                                    'data-bs-toggle' => 'tooltip',
                                                    'data-bs-placement' => 'bottom',
                                                    'title' => Yii::t('views', 'Отменить'),
                                                    'data-message' => Yii::t('views', 'Вы действительно хотите отменить заявку?'),
                                                    'data-url' => Url::to(['changeemailcancel', 'id' => $model->id]),
                                                    'data-pjaxContainer' => '#emailchangesList',
                                                    'onclick' => 'workWithRecord($(this))',
                                                ]
                                            );
                                        }
                                    ],
                                ],
                            ],
                        ]);
                    Pjax::end();
                ?>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <?php if (Yii::$app->getUser()->getIdentity()->groups) {
                    echo Html::tag('h4', Yii::t('views', 'Доступные группы'));
                    foreach (Yii::$app->getUser()->getIdentity()->groups as $group) {
                        echo Html::tag('span', $group, ['class' => 'badge bg-primary me-2']);
                    }
                } else {
                    echo Yii::t('views', 'Вы не привязанны к группе. Пожалуйста, обратитесь к администратору');
                } ?>
            </div>
        </div>
    </div>

    <div class="d-block d-md-none col-12">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <?= Yii::t('views', 'Роли'); ?>
                </h5>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <?php if ($model->getEntity()->rights) {
                    $rightsItemsName = ArrayHelper::map(Yii::$app->getAuthManager()->getRoles(), 'name', 'description');

                    foreach ($model->getEntity()->rights as $right) {
                        echo Html::tag('span', $rightsItemsName[$right->item_name], ['class' => 'badge bg-success me-2']);
                    }
                } ?>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="card-title mb-0"><?= Yii::t('views', 'Привязанная группа'); ?></h5>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <?php if (Yii::$app->getUser()->getIdentity()->group) {
                    echo Yii::$app->getUser()->getIdentity()->groups[Yii::$app->getUser()->getIdentity()->group];
                } ?>
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="h6 card-title d-inline">
                    <?= Yii::t('views', 'Последний сеанс'); ?>
                </h5>
            </div>
            <hr class="my-0" />
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-1">
                        <span class="bi bi-alarm me-1"></span>
                        <?= Yii::t('views', 'Время: {time}', [
                            'time' => Yii::$app->formatter->asDatetime($model->getEntity()->lastAuth?->created_at)
                        ]); ?>
                    </li>
                    <li class="mb-1">
                        <span class="bi bi-ethernet me-1"></span>
                        <?= Yii::t('views', 'IP: {ip}', ['ip' => $model->getEntity()->lastAuth?->ip]); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
