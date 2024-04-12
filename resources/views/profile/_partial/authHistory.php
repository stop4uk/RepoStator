<?php

/**
 * @var \app\entities\user\UserSessionEntity[] $data
 */

use yii\data\ArrayDataProvider;
use yii\widgets\Pjax;

use app\widgets\GridView;

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
                'format' => ['date', Yii::$app->settings->get('system', 'app_language_dateTime')]
            ],
            'ip',
            [
                'attribute' => 'client',
                'contentOptions' => ['class' => 'small'],
                'value' => fn($data) => strlen(strip_tags($data->client)) > 75 ? mb_substr(strip_tags($data->client), 0, 75).' ...' : strip_tags($data->client)
            ],
        ],
    ]);
Pjax::end();
