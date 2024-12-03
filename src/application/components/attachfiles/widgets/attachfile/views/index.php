<?php

use yii\widgets\Pjax;
use yii\grid\{
    GridView,
    SerialColumn,
    ActionColumn
};
use yii\helpers\Url;
use yii\bootstrap\Html;

use common\helpers\PagerHelper;
use common\attachfiles\{
    AttachFileUploadForm,
    widgets\fileupload\FileUploadWidget
};

/**
 * @var string $blockTitle Общий заголовок для блока
 * @var string $uploadButtonTitle Текст на кнопке выбора доков для загрузки
 * @var array $canAttached Список типов доков, которые можно загрузить
 * @var bool $canDeleted Возможность удаления файлов
 * @var \yii\db\BaseActiveRecord $parentModel Модель, к которой привязыается виджет
 * @var \yii\data\ArrayDataProvider $dataProvider Данные по уже загруженным и, находящимся в статусе ACTIVE файлам
 */

$uploadModel = new AttachFileUploadForm([
    'modelClass' => $parentModel::class,
    'modelKey' => $parentModel->{$parentModel->modelKey}
]);

?>

<?php
Pjax::begin(['id' => 'attachedFileList']);
$this->registerJs(<<<JS
         $('.pjax-delete-link').on('click', function(e) {
             e.preventDefault();
             var deleteUrl = $(this).attr('delete-url');
             var pjaxContainer = $(this).attr('pjax-container');
             var result = confirm('Вы действительно хотите удалить текущий документ?');                                
             if(result) {
                 $.ajax({
                     url: deleteUrl,
                     type: 'post',
                 }).done(function(data) {
                     $.pjax.reload('#' + $.trim(pjaxContainer), {timeout: 3000});
                 });
             }
         });
JS);
?>
    <div class="card mb-30">
        <div class="card-body">
            <div class="card-header">
                <?php if ($canAttached): ?>
                    <div class="dropdown">
                        <button type="button" class="btn btn-outline-success mr-2 float-right" id="attachFileToOrderButton" data-toggle="dropdown" aria-expanded="false">
                            <i class="lni lni-add-file"></i> <?= $uploadButtonTitle ?>
                        </button>
                        <div class="dropdown-menu">
                            <?php
                            foreach ($canAttached as $type => $params) {
                                $actionParams = [
                                    'modelClass' => $parentModel::class,
                                    'modelKey' => (string)$parentModel->{$parentModel->modelKey},
                                    'modelType'  => $type
                                ];

                                echo FileUploadWidget::widget([
                                    'model' => $uploadModel,
                                    'attribute' => 'uploadFile',
                                    'url' => Url::to(['attachfile', 'params' => base64_encode(serialize($actionParams))]),
                                    'buttonName' => $params['name'],
                                    'options' => [
                                        'id' => 'fileUpWidget' . rand(),
                                    ],
                                    'clientOptions' => [
                                        'maxFileSize' => 2000000,
                                    ],
                                    'clientEvents' => [
                                        'fileuploaddone' => 'function(e, data) {
                                            let response = $.parseJSON(data.result);
                                            if (response.status == "success") {
                                                $.pjax.reload({container:"#attachedFileList"});
                                            } else {
                                                let string = "В процессе загрузки файлов возникли ошибки:\r\n\r\n";
                                                $.each(response.errors, function(index, value){
                                                    string = string + value + "\r\n";
                                                });
                                            
                                                alert(string);
                                            }
                                        }',
                                        'fileuploadfail' => 'function(e, data) {
                                            alert("В процессе загрузки файла произошла ошибка. Пожалуйста, обратитесь к администратору");
                                        }',
                                    ],
                                ]);
                            } ?>
                        </div>
                    </div>
                <?php endif; ?>

                <h5 class="card-title"><?= $blockTitle ?></h5>
            </div>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'pager' => PagerHelper::defaultConfig(),
                'layout' => '{items}<div class="text-center">{pager}</div>',
                'tableOptions' => ['class' => 'table m-0 table-hover'],
                'columns' => [
                    ['class' => SerialColumn::class],
                    'name' => [
                        'attribute' => 'name',
                        'label' => 'Название',
                        'format' => 'html',
                        'value' => function($model) {
                            return $model->name . Html::tag('span', '#'.Yii::$app->formatter->asShortSize($model['file_size']), ['class' => 'text-muted small ml-1']);
                        }
                    ],
                    'file_type' => [
                        'attribute' => 'file_type',
                        'label' => 'Тип',
                        'value' => function($model) use($parentModel) {
                            return $parentModel->getAttachedFileTypeName($model->file_type);
                        }
                    ],
                    'file_tags' => [
                        'attribute' => 'file_tags',
                        'label' => 'Теги',
                        'format' => 'html',
                         'value' => function($model) {
                            if ($model->file_tags) {
                                if (is_array($model->file_tags)) {
                                    $tags = '';
                                    foreach ($model->file_tags as $tag) {
                                        $tags .= Html::tag('span', $tag, ['class' => 'badge border p-1 m-1']);
                                    }

                                    return $tags;
                                }

                                return Html::tag('span', $model->file_tags, ['class' => 'badge border p-1']);

                            }
                         }
                    ],
                    [
                        'attribute' => 'created_at',
                        'label' => 'Загружен',
                        'value' => fn($model) => Yii::$app->getFormatter()->asDatetime($model->created_at)
                    ],
                    [
                        'class' => ActionColumn::class,
                        'headerOptions' => ['width' => '10%'],
                        'template' => '{download}{delete}',
                        'buttons' => [
                            'download' => function ($url, $model) use ($parentModel) {
                                $actionParams = [
                                    'modelClass' => $parentModel::class,
                                    'modelKey' => (string)$parentModel->{$parentModel->modelKey},
                                    'hash' => $model['file_hash']
                                ];

                                return Html::a(
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-download icon wh-15"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>',
                                    ['getattachfile', 'params' => base64_encode(serialize($actionParams))],
                                    [
                                        'class' => 'btn btn-link text-success p-0',
                                        'data-pjax' => 0,
                                        'title' => 'Скачать'
                                    ]
                                );
                            },
                            'delete' => function ($url, $model) use ($parentModel, $canDeleted) {
                                if (!$canDeleted) {
                                    return null;
                                }

                                $actionParams = [
                                    'modelClass' => $parentModel::class,
                                    'modelKey' => (string)$parentModel->{$parentModel->modelKey},
                                    'hash' => $model['file_hash']
                                ];

                                return Html::tag(
                                    'span',
                                    '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash icon wh-15"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>',
                                    [
                                        'class' => 'btn btn-link text-success p-0 pjax-delete-link',
                                        'title' => 'Удалить',
                                        'delete-url' => Url::to(['detachfile', 'params' => base64_encode(serialize($actionParams))]),
                                        'pjax-container' => 'attachedFileList',
                                    ]
                                );
                            },
                        ]
                    ]
                ],
            ]); ?>
        </div>
    </div>
<?php Pjax::end(); ?>