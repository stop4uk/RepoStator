<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\modules\users\components\rbac\{
    items\Permissions,
    RbacHelper
};

/**
 * @var \app\modules\reports\models\ReportModel $model
 * @var \yii\web\View $this
 */

$this->title = Yii::t('views', 'Просмотр отчета');

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
];

?>
    <div class="card">
        <div class="card-body">
            <?php
                $createdAt = Yii::$app->getFormatter()->asDatetime($model->getEntity()->created_at);
                echo Html::tag('span', "{$model->getAttributeLabel('created_at')}: {$createdAt}");

                if ($model->getEntity()->updated_at) {
                    $updatedAt = Yii::$app->getFormatter()->asDatetime($model->getEntity()->updated_at);
                    echo Html::tag('span', "{$model->getAttributeLabel('updated_at')}: {$updatedAt}", ['class' => 'd-block']);
                }
            ?>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <?php
                if (!$model->getEntity()->record_status) {
                    echo Html::tag('div', Yii::t('views', 'Данная запись НЕАКТИВНА'), ['class' => 'alert alert-danger text-center', 'role' => 'alert']);
                }

                echo $this->render('_partial/form', compact('model'));

                if (!$model->getEntity()->record_status) {
                    $ruleArray = $model->getEntity()->toArray(['created_uid', 'created_gid', 'record_status']);

                    if (RbacHelper::canArray([
                        Permissions::REPORT_ENABLE_MAIN,
                        Permissions::REPORT_ENABLE_GROUP,
                        Permissions::REPORT_ENABLE_ALL,
                    ], $ruleArray)) {
                        echo Html::a(
                            Yii::t('views', 'Сделать карточку активной'),
                            Url::to(['enable', 'id' => $model->getEntity()->id]),
                            [
                                'class' => 'btn btn-dark w-100',
                                'id' => "enableButton_{$model->getEntity()->id}"
                            ]
                        );
                    }
                }
            ?>
        </div>
    </div>

<?php
    $this->registerJs(<<<JS
        $("form").on("beforeSend", function(e) {e.preventDefault();});
        $("input, select, textarea").attr({"disabled": true, "readonly": true});
    $(" button, .new-repeater, .remove").remove();
JS);
