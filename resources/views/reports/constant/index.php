<?php

/**
 * @var \app\search\report\ConstantSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\widgets\{
    Pjax,
    ListView
};
use yii\bootstrap5\Html;

use app\widgets\Pager;

$this->title = Yii::t('views', 'Список констант');

?>

    <div class="d-flex justify-content-end mb-2">
        <?php
            if ( Yii::$app->getUser()->can('constant.create') ) {
                echo Html::a(Yii::t('views', 'Новая константа'), ['create'], ['class' => 'btn btn-primary pt-1 pb-1 me-2']);
            }

            echo Html::tag('i', '', [
                'id' => 'searchCardButton',
                'class' => 'btn btn-danger bi bi-funnel',
                'data-bs-toggle' => 'tooltip',
                'data-bs-placement' => 'bottom',
                'title' => Yii::t('views', 'Фильтры поиска'),
            ]);
        ?>
    </div>

<?php
    Pjax::begin(['id' => 'constantList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]);
        echo $this->render('_partial/search', ['searchModel' => $searchModel]);
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'emptyText' => Yii::t('views', 'Константы для просмотра отсутствуют'),
            'emptyTextOptions' => ['class' => 'alert alert-danger text-center fw-bold'],
            'itemView' => '_partial/list_item',
            'options' => ['class' => 'row'],
            'itemOptions' => ['class' => 'col-12 col-xl-6'],
        ]);
        echo Pager::widget([
            'pagination' => $dataProvider->getPagination(),
        ]);
    Pjax::end();






