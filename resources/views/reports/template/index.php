<?php

/**
 * @var \app\search\report\TemplateSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

use yii\bootstrap5\Html;
use yii\widgets\Pjax;
use yii\widgets\ListView;

use app\widgets\Pager;

$this->title = Yii::t('views', 'Список шаблонов');

?>
    <div class="d-flex justify-content-end mb-2">
        <?php
            if ( Yii::$app->getUser()->can('template.create') ) {
                echo Html::a(Yii::t('views', 'Новый шаблон'), ['create'], ['class' => 'btn btn-primary pt-1 pb-1 me-2']);
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
    Pjax::begin(['id' => 'templatesList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]);
        echo $this->render('_partial/search', ['searchModel' => $searchModel]);
        echo ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'emptyText' => Yii::t('views', 'Шаблоны отсутствуют'),
            'emptyTextOptions' => ['class' => 'alert alert-danger text-center fw-bold'],
            'itemView' => '_partial/list_item',
            'options' => ['class' => 'row'],
            'itemOptions' => ['class' => 'col-12'],
            'pager' => ['class' => Pager::class]
        ]);
        echo Pager::widget([
            'pagination' => $dataProvider->getPagination(),
        ]);

    Pjax::end();