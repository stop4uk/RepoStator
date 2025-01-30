<?php

use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\modules\reports\widgets\structform\StructFormWidget;

/**
 * @var \app\modules\reports\models\DataModel $model
 */

$this->params['breadcrumbs'] = [
    ['label' => Yii::t('views', 'Отчеты'), 'url' => Url::to(['/reports'])],
    ['label' => Yii::t('views', 'Передача'), 'url' => Url::to(['/reports/send'])],
    $model->group->name,
    $model->report->name
];

?>

<div class="card">
    <div class="card-body py-0">
        <table class="table table-borderless">
            <tr>
                <td class="pt-2"><?= Yii::t('views', 'Начало отчетного периода'); ?></td>
                <td class="text-end py-0 fw-bold"><?= date('d.m.Y H:i', $model->report_datetime); ?></td>
            </tr>
            <tr>
                <td class="py-0"><?= Yii::t('views', 'Группа'); ?></td>
                <td class="text-end py-0 fw-bold"><?= $model->group->name; ?></td>
            </tr>
            <tr>
                <td class="py-0"><?= Yii::t('views', 'Пользователь'); ?></td>
                <td class="text-end py-0 fw-bold"><?= Yii::$app->getUser()->getIdentity()->shortName; ?></td>
            </tr>

            <?php if ($model->form_control ): ?>
                <tr>
                    <td colspan="2" class="py-0 text-center h3">
                        <?= Html::tag('code', Yii::t('views', 'Режим контроля')); ?>
                    </td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>
<div class="card mt-3">
    <div class="card-body">
        <?= StructFormWidget::widget([
            'model' => $model,
            'formId' => 'reportwork-form',
            'formField' => 'content',
        ]); ?>
    </div>
</div>

