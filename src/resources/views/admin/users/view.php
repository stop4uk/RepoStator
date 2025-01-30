<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\modules\users\components\rbac\{
    items\Permissions,
    RbacHelper
};

/**
 * @var \app\modules\users\models\UserModel $model
 * @var \yii\web\View $this
 */

$this->title = Yii::t('views', 'Просмотр пользователя');

$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Пользователи'), 'url' => Url::to(['/admin/users'])],
];

?>

    <div class="card">
        <div class="card-body">
            <?php
                if (!$model->getEntity()->record_status) {
                    echo Html::tag('div', Yii::t('views', 'Данная запись НЕАКТИВНА'), ['class' => 'alert alert-danger text-center', 'role' => 'alert']);
                }

                echo $this->render('_partial/form', compact('model'));

                if (!$model->getEntity()->record_status) {
                    $ruleArray = $model->getEntity()->toArray(['id', 'record_status']);

                    if (RbacHelper::canArray([Permissions::ADMIN_USER_ENABLE_GROUP, Permissions::ADMIN_USER_ENABLE_ALL], $ruleArray)) {
                        echo Html::a(Yii::t('views', 'Сделать карточку активной'), Url::to(['enable', 'id' => $model->getEntity()->id]), ['class' => 'btn btn-dark w-100']);
                    }
                }
            ?>
        </div>
    </div>

<?php
    $this->registerJs(<<<JS
        $("form").on("beforeSend", function(e) {e.preventDefault();});
        $("input, select, textarea").attr({"disabled": true, "readonly": true});
        $("button, .new-repeater, .remove").remove();
JS);
