<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

/**
 * @var \app\models\report\TemplateModel $model
 * @var \yii\web\View $this
 */

$this->title = Yii::t('views', 'Просмотр шаблона');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Шаблоны'), 'url' => Url::to(['/reports/template'])],
];

?>

    <div class="card">
        <div class="card-body">
            <?php
            if ( !$model->getEntity()->record_status ) {
                echo Html::tag('div', Yii::t('views', 'Данная запись НЕАКТИВНА'), ['class' => 'alert alert-danger text-center', 'role' => 'alert']);
            }

            echo $this->render('_partial/form', compact('model'));

            if ( !$model->getEntity()->record_status ) {
                $ruleArray = $model->getEntity()->toArray(['created_uid', 'created_gid', 'record_status']);

                if (
                    Yii::$app->getUser()->can('template.enable.main', $ruleArray)
                    || Yii::$app->getUser()->can('template.enable.group', $ruleArray)
                    || Yii::$app->getUser()->can('template.enable.all', $ruleArray)
                ) {
                    echo Html::a(Yii::t('views', 'Сделать карточку активной'), Url::to(['enable', 'id' => $model->getEntity()->id]), ['class' => 'btn btn-dark w-100']);
                }
            }
            ?>
        </div>
    </div>

<?php
    $this->registerJs(<<<JS
        $("form").on("beforeSend", function(e) {e.preventDefault();});
        $("input, select").attr({"disabled": true, "readonly": true});
        $("button").remove();
JS);
