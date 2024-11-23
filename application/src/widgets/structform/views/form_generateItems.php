<?php

/**
 * @var \yii\bootstrap5\ActiveForm $form
 * @var \app\base\BaseModel $model
 * @var array $constants
 * @var string $formField
 * @var array $elements
 * @var int $id
 */

use yii\bootstrap5\Html;
if ( isset($elements['###']) ) {
    foreach ($elements['###'] as $item) {
        if ( isset($constants[$item]) ) {
            echo Html::beginTag('div', ['class' => 'col-auto col-md-4 col-xl-3']);
                echo $form->field($model, $formField . "[$item]")->input("number")->label($constants[$item]['name'], ['class' => ['form-label']]);
            echo Html::endTag('div');
        }
    }

    unset($elements['###']);
}

?>

<?php if ( $elements ): foreach ($elements as $block => $groups): ?>
    <div class="col-12 mb-3">
        <div class="accordion" id="reportWorkAccordion<?= $id . $block ?>">
            <?php foreach($groups as $groupName => $items): ?>
                <?php $identifier = rand(0, 99999); ?>

                <div class="accordion-item">
                    <button class="small pt-1 pb-1 accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $identifier ?>" aria-expanded="false" aria-controls="collapse<?= $id . $groupName ?>">
                        <?= $groupName ?>
                    </button>
                </div>

                <div id="collapse<?= $identifier ?>" class="accordion-collapse collapse" aria-labelledby="heading<?= $identifier ?>" data-bs-parent="#reportWorkAccordion<?= $id . $block ?>">
                    <div class="accordion-body ps-0 pe-0">
                        <div class="row d-flex justify-content-start">
                            <?php foreach ($items as $item): if (isset($constants[$item])): ?>
                                <div class="col-auto col-md-4 col-xl-3">
                                    <?= $form->field($model, $formField . "[$item]")->input('number')->label($constants[$item]['name'] ?? $item, ['class' => 'form-label']); ?>
                                </div>
                            <?php endif; endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endforeach; endif;