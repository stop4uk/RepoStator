<?php

use yii\bootstrap5\Html;

/**
 * @var string $statusCode
 * @var string $message
 */

?>

<div class="row">
    <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
        <div class="d-table-cell align-middle">
            <div class="text-center mt-4">
                <h2 class="h2"><?= Yii::$app->settings->get('system', 'app_name'); ?>. <span class="text-muted smaller"><?= $statusCode; ?></span></h2>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="m-1 mb-0">
                        <h4 class="text-center"><?= $message; ?></h4>
                        <div class="d-grid">
                            <?php if ( $link = Yii::$app->getRequest()->getReferrer() ) {
                                echo Html::a( Yii::t('views', 'Назад'), $link, ['class' => 'btn btn-primary mt-3']);
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
