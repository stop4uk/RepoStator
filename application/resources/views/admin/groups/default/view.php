<?php

/**
 * @var \app\models\group\GroupModel $model
 * @var \yii\web\View $this
 */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$this->title = Yii::t('views', 'Просмотр группы');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Группы'), 'url' => Url::to(['/admin/groups'])],
];

?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?php
                    if ( !$model->getEntity()->record_status ) {
                        echo Html::tag('div', Yii::t('views', 'Данная запись НЕАКТИВНА'), ['class' => 'alert alert-danger text-center', 'role' => 'alert']);
                    }

                    echo $this->render('_partial/form', compact('model'));

                    if ( !$model->getEntity()->record_status) {
                        echo Html::a(Yii::t('views', 'Сделать карточку активной'), Url::to(['enable', 'id' => $model->getEntity()->id]), ['class' => 'btn btn-dark w-100']);
                    }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
$this->registerJs(<<<JS
    $("form").on("beforeSend", function(e) {e.preventDefault();});
    $("input, select, textarea").attr({"disabled": true, "readonly": true});
    $("button, .new-repeater, .remove").remove();
JS);
