<?php

use yii\bootstrap5\Html;

/**
 * @var \app\useCases\reports\entities\ReportEntity $model
 */

$timePeriodMessage = Yii::t(
    'views',
    $model->timePeriod ? 'Период с {start} по {end}' : 'Без ограничений передачи', [
        'start' => isset($model->timePeriod->start) ? date('d.m.Y H:i', $model->timePeriod->start) : null,
        'end' => isset($model->timePeriod->end) ? date('d.m.Y H:i', $model->timePeriod->end) : null
    ]
);

$reportName = $model->name . Html::tag('span', ' #' . $timePeriodMessage, ['class' => 'small text-primary fw-bold']);
?>

<div class="card">
    <div class="card-body">
        <h5 role="button" class="mb-0" id="linkInfoBlock_<?= $model->id; ?>" data-id="<?= $model->id; ?>"><i id="arrowInfoBlock_<?= $model->id; ?>" class="bi bi-arrow-down-circle" data-show="0"></i> <?= $reportName; ?></h5>
        <div class="row d-none" id="reportInfoBlock_<?= $model->id ?>">
            <hr />
            <h5 class="text-center text-muted"><?= Yii::t('views', 'Выберите группу, от имени которой необходимо передать отчет'); ?></h5>
            <?php foreach ($model->canAddedFor as $item): ?>
                <div class="<?= (count($model->canAddedFor) == 1) ? 'col-12' : 'col-6 col-xl-4' ?> mb-2">
                    <?= Html::a($item['groupName'], ['process', 'report_id' => $model->id, 'group_id' => $item['groupId'], 'report_datetime' => $model->timePeriod->start ?? time()], ['class' => 'btn btn-outline-primary w-100', 'data-pjax' => 0]); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php

$this->registerJs(<<<JS
    $("[id^='linkInfoBlock_']").on("click", function(){
        let dataId = $(this).attr("data-id");
        
        if ( $("#arrowInfoBlock_" + dataId).attr("data-show") == 0 ) {
            $("[id^='reportInfoBlock_']").addClass("d-none");
            $("[id^='arrowInfoBlock_']").removeClass("bi-arrow-up-circle").addClass("bi-arrow-down-circle").attr("data-show", 0);
            
            $("#arrowInfoBlock_" + dataId).removeClass("bi-arrow-down-circle").addClass("bi-arrow-up-circle").attr("data-show", 1);
            $("#reportInfoBlock_" + dataId).removeClass("d-none");
        } else {
            $("#arrowInfoBlock_" + dataId).removeClass("bi-arrow-up-circle").addClass("bi-arrow-down-circle").attr("data-show", 0);
            $("#reportInfoBlock_" + dataId).addClass("d-none");    
        }   
    });
JS);