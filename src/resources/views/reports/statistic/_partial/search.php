<?php

use yii\helpers\Url;
use yii\bootstrap5\{
    Html,
    ActiveForm
};
use kartik\select2\Select2;

use app\helpers\CommonHelper;
use app\modules\reports\helpers\JobHelper;

/**
 * @var \app\modules\reports\search\StatisticSearch $searchModel
 */

$resource = Url::to(['/statistic']);
$form = ActiveForm::begin([
    'id' => 'searchForm',
    'options' => [
        'data-pjax' => true,
        'autocomplete' => 'off'
    ]
]); ?>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-12 col-md-4">
                <?= $form->field($searchModel, 'job_status')->dropDownList(CommonHelper::getFilterReplaceData(JobHelper::statuses()), ['prompt' => Yii::t('views', 'Выберите')]); ?>
            </div>
            <div class="col-12 col-md-4">
                <?= $form->field($searchModel, 'report_id')->widget(Select2::class, [
                    'data' => $searchModel->reports,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-12 col-md-4">
                <?= $form->field($searchModel, 'template_id')->widget(Select2::class, [
                    'data' => $searchModel->templates,
                    'options' => ['placeholder' => '', 'multiple' => false],
                    'pluginOptions' => ['allowClear' => true],
                ]); ?>
            </div>
            <div class="col-6">
                <div class="d-grid gap-2">
                    <?= Html::submitButton(Yii::t('views', 'Поиск'), ['class' => 'btn btn-dark']) ?>
                </div>
            </div>
            <div class="col-6">
                <div class="d-grid gap-2">
                    <?= Html::a(Yii::t('views', 'Очистить'), $resource, ['class' => 'btn btn-danger']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end();
