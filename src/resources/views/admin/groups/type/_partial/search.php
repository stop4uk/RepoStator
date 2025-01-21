<?php

use app\helpers\CommonHelper;
use yii\bootstrap5\{ActiveForm, Html};
use yii\helpers\Url;

/**
 * @var \app\modules\admin\search\GroupTypeSearch $searchModel
 */

$resource = Url::to(['/admin/groups/type']);

?>

<div class="card <?= CommonHelper::getDataShowAttribute($searchModel) ? '' : 'd-none'; ?>" id="searchCard" data-show="<?= CommonHelper::getDataShowAttribute($searchModel); ?>">
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'searchForm',
            'options' => [
                'data-pjax' => true,
                'autocomplete' => 'off'
            ]
        ]); ?>
        <div class="row">
            <div class="col-12 col-md-6">
                <?= $form->field($searchModel, 'name'); ?>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-md-block">&nbsp;</label>
                    <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark']) ?>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="d-grid gap-2">
                    <label class="form-label mb-0 d-none d-md-block">&nbsp;</label>
                    <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>