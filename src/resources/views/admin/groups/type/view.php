<?php

use yii\helpers\Url;

/**
 * @var \app\modules\users\models\GroupTypeModel $model
 * @var \yii\web\View $this
 */

$this->title = Yii::t('views', 'Просмотр типа группы');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Группы'), 'url' => Url::to(['/admin/groups'])],
    ['label' => Yii::t('views', 'Типы'), 'url' => Url::to(['/admin/groups/type'])],
];

?>

    <div class="card">
        <div class="card-body">
            <?= $this->render('_partial/form', compact('model')); ?>
        </div>
    </div>


<?php
    $this->registerJs(<<<JS
        $("form").on("beforeSend", function(e) {e.preventDefault();});
        $("input, select, textarea").attr({"disabled": true, "readonly": true});
        $("button, .new-repeater, .remove").remove();
JS);
