<?php

use yii\bootstrap5\Html;

use app\components\assets\ClearAsset;
use app\widgets\AlertToast;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

ClearAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => Yii::$app->settings->get('system', 'meta_description')]);
$this->registerMetaTag(['name' => 'keywords', 'content' => Yii::$app->settings->get('system', 'meta_keywords')]);
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
                <main class="w-100 m-auto">
                    <div class="container d-flex flex-column">
                        <?= $content ?>
                    </div>
                </main>

                <?= $this->render('_blocks/footer'); ?>
            <?php $this->endBody() ?>
        </body>
    </html>
<?php
$this->endPage();
