<?php

use yii\helpers\Json;
use yii\bootstrap5\{
    Html,
    Breadcrumbs
};

use app\components\assets\MainAsset;
use app\widgets\AlertToast;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

MainAsset::register($this);

$messages = Json::htmlEncode([
    'forbiddenTemplate' => Yii::t('exceptions', 'У Вас отсутствуют права доступа для осуществления данного действия. Пожалуйста, обратитесь к администратору')
]);

$this->registerJs(<<< JS
    var langMessages = $messages;
JS, $this::POS_BEGIN, 'langMessages');


$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => env('YII_APP_DESC', Yii::$app->settings->get('system', 'meta_description'))]);
$this->registerMetaTag(['name' => 'keywords', 'content' => env('YII_APP_META_KEY', Yii::$app->settings->get('system', 'meta_keywords'))]);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <title><?= Html::encode(Yii::$app->settings->get('system', 'app_name')) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column vh-100">
    <?php $this->beginBody() ?>
    <?= AlertToast::widget() ?>
    <div id="loadingData" class="text-center">
        <div class="spinner-border text-light" role="status">
            <span class="visually-hidden"><?= Yii::t('views', 'Загрузка'); ?></span>
        </div>
    </div>
    <div id="hidescreen"></div>
    <div class="position-relative" aria-atomic="true" aria-live="polite">
        <div class="toast-container position-absolute top-0 end-0 p-3">
            <div class="text-white" id="mainToast" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="mainToastBody"></div>
                    <button class="me-2 m-auto btn-close d-none" type="button" data-bs-dismiss="toast" aria-label="<?= Yii::t('views', 'Закрыть'); ?>" id="btn_closeMainToast">
                </div>
            </div>
        </div>
    </div>

    <div class="wrapper">
        <?= $this->render('_blocks/main_panel_left'); ?>
        <div class="main">
            <?= $this->render('_blocks/main_panel_top'); ?>
            <main class="content">
                <div class="container-fluid p-0">
                    <div class="row mb-3">
                        <div class="col-12 d-grid gap-2 justify-content-center gap-md-0 d-md-flex <?= ($this->title) ? 'justify-content-md-between' : 'justify-content-md-end' ?>">
                            <?php if ($this->title): ?>
                                <h3 class="fw-bold mb-0 text-center text-md-start"><?= $this->title;?></h3>
                            <?php endif; ?>

                            <?php if (isset($this->params['breadcrumbs']) ): ?>
                                <?= Breadcrumbs::widget([
                                    'options' => ['class' => 'mb-0 d-flex justify-content-center justify-content-md-end'],
                                    'homeLink' => ['label' => Yii::t('modules', 'Главная'), 'url' => '/'],
                                    'links' => $this->params['breadcrumbs'],
                                ]) ?>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?= $content; ?>
                </div>
            </main>

            <?= $this->render('_blocks/footer'); ?>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="modalWindow">
        <div class="modal-dialog" id="modalWindow_dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div id="modalWindow_header"></div>
                    <button id="modalWindow_closeButton" type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div id="modalWindow_content" class="modal-body"></div>
            </div>
        </div>
    </div>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php
$this->endPage();
