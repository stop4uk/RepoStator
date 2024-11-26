<?php

use yii\helpers\Url;

/**
 * @var \models\group\GroupTypeModel $model
 * @var \yii\web\View $this
 */

$this->title = Yii::t('views', 'Просмотр типа группы');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Группы'), 'url' => Url::to(['/admin/groups'])],
    ['label' => Yii::t('views', 'Типы'), 'url' => Url::to(['/admin/groups/type'])],
];

?>


<?php
    $this->registerJs(<<<JS
        $("form").on("beforeSend", function(e) {e.preventDefault();});
        $("input, select, textarea").attr({"disabled": true, "readonly": true});
        $("button, .new-repeater, .remove").remove();
JS);
