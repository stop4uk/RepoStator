<?php

use yii\data\ArrayDataProvider;
use yii\widgets\Pjax;

use app\widgets\GridView;

/**
 * @var \app\useCases\users\entities\user\UserSessionEntity[] $data
 */

$dataProvider = new ArrayDataProvider([
    'allModels' => $data,
    'pagination' => [
        'pageSize' => 25,
    ]
]);

Pjax::begin(['id' => 'authList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]);
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table'],
        'columns' => [
            [
                'attribute' => 'created_at',
                'contentOptions' => ['width' => '25%'],
                'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')]
            ],
            [
                'attribute' => 'ip',
                'contentOptions' => ['width' => '15%'],
            ],
            [
                'attribute' => 'client',
                'contentOptions' => ['class' => 'small'],
                'value' => fn($data) => strlen(strip_tags($data->client)) > 75 ? mb_substr(strip_tags($data->client), 0, 75).' ...' : strip_tags($data->client)
            ],
        ],
    ]);
Pjax::end();
