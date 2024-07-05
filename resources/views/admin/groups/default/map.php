<?php

/**
 * @var \yii\web\View $this
 * @var \yii\db\ActiveQuery $query
 * @var array $groups
 */

use yii\helpers\Url;
use klisl\nestable\Nestable;

$this->title = Yii::t('views', 'Карта подчинения');
$this->params['breadcrumbs'] = [
    Yii::t('views', 'Админпанель'),
    ['label' => Yii::t('views', 'Группы'), 'url' => Url::to(['/admin/groups'])],
];

?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <?= Nestable::widget([
                    'type' => Nestable::TYPE_WITH_HANDLE,
                    'query' => $query,
                    'modelOptions' => [
                        'name' => function($model) use ($groups) {
                            return $groups[$model->group_id] ?? null;
                        }
                    ],
                    'handleLabel' => '<div class="dd-handle dd3-handle bg-primary">&nbsp;</div>',
                    'pluginOptions' => ['maxDepth' => 100],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<?php

$this->registerJs(<<<JS
    $('.dd').nestable('collapseAll');
JS);