<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

/**
 * @var \app\entities\LogEntity $entity
 */

$this->title = Yii::t('views', 'Сообщение лога');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Логи'), 'url' => Url::to(['/admin/logs'])],
    Yii::t('views', 'Просмотр')
];

?>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12">
                <div class="d-grid gap-2 gap-md-0 text-center text-md-start d-md-flex justify-content-md-between">
                    <div class="h4">
                        <?php
                            $time = explode('.', $entity->log_time)[0];
                            echo Html::tag('code', Yii::$app->formatter->asDatetime($time));
                        ?>
                    </div>
                    <div class="h4">
                        <code><?= Yii::t('entities', 'Уровень: {level}', ['level' => $entity->level]); ?></code>
                    </div>
                    <div class="h4">
                        <code><?= Html::decode($entity->category); ?></code>
                    </div>
                </div>
            </div>
            <hr />
            <div class="col-12 mb-2">
                <div class="border rounded bg-secondary p-2">
                    <samp class="text-white">
                        <?= Html::decode($entity->prefix); ?>
                    </samp>
                </div>
            </div>
            <div class="col-12">
                <div class="border rounded bg-secondary p-2">
                    <samp class="text-white">
                        <?= Html::decode($entity->message); ?>
                    </samp>
                </div>
            </div>
        </div>
    </div>
</div>
