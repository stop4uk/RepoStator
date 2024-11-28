<?php

use yii\widgets\Pjax;
use yii\widgets\ListView;

use app\widgets\Pager;

/**
 * @var \app\useCases\reports\search\SendSearch $searchModel
 * @var \yii\data\ActiveDataProvider $dataProvider
 */

$this->title = Yii::t('views', 'Выбор отчета для передачи');

?>

<?php Pjax::begin(['id' => 'reportWorkList', 'enablePushState' => false, 'clientOptions' => ['method' => 'POST']]) ?>
<?= $this->render('_partial/search', ['searchModel' => $searchModel]); ?>

<p class="text-muted text-justify small">
    <i class="bi bi-info-circle text-primary fw-bold"></i> <?= Yii::t('views', 'Присутствуют только те отчеты, которые Вам доступны и, для которых возможна передача сведений. ' .
    'Если, сведения уже переданы в текущий расчетный период времени (исходя из заданных ограничений) - отчет будет отсутствовать в данном ' .
    'списке. При этом, если, у Вас есть права на осуществления контроля, Вы можете поглядеть все ранее переднные сведения по отчетам'); ?>
</p>

<div class="row">
    <div class="col-12">
        <?= ListView::widget([
            'dataProvider' => $dataProvider,
            'layout' => '{items}',
            'emptyText' => Yii::t('views', 'Отчеты для передачи отсутствуют'),
            'emptyTextOptions' => [
                'class' => 'alert alert-success text-center fw-bold'
            ],
            'viewParams' => [
                'groups' => $searchModel->groups
            ],
            'itemView' => '_partial/reportItemSelect',
            'options' => ['class' => 'row'],
            'itemOptions' => ['class' => 'col-12'],
            'pager' => [
                'class' => Pager::class
            ]
        ]); ?>
    </div>
</div>
<?php Pjax::end();
