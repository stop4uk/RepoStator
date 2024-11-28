<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    ActiveForm
};

/**
 * @var \app\useCases\reports\search\SendSearch $searchModel
 */

$resource = Url::to(['/reports/work']);

?>

<div class="card mb-3">
    <div class="card-body">
        <?php $form = ActiveForm::begin([
            'id' => 'searchForm',
            'options' => [
                'data-pjax' => true,
                'autocomplete' => 'off'
            ]
        ]); ?>
            <div class="row">
                <div class="col-12 col-md-8">
                    <?= $form->field($searchModel, 'name'); ?>
                </div>
                <div class="col-6 col-md-2">
                    <?php
                        echo Html::label('&nbsp;', '', ['class' => 'form-label d-none d-md-block']);
                        echo Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark w-100']);
                    ?>
                </div>
                <div class="col-6 col-md-2">
                    <?php
                    echo Html::label('&nbsp;', '', ['class' => 'form-label d-none d-md-block']);
                    echo Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger'])
                    ?>
                </div>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>