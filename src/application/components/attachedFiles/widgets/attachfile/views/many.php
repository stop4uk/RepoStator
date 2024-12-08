<?php

use yii\widgets\Pjax;
use yii\grid\{
    SerialColumn,
    ActionColumn
};
use yii\helpers\Url;
use yii\bootstrap5\Html;

use app\widgets\GridView;
use app\components\attachedFiles\{
    AttachFileHelper,
    AttachFileUploadForm,
    widgets\fileupload\FileUploadWidget
};

/**
 * @var string $blockTitle Общий заголовок для блока
 * @var string $uploadButtonTitle Текст на кнопке выбора доков для загрузки
 * @var string $uploadButtonOptions Классы для кнопки загрузки
 * @var array $canAttached Список типов доков, которые можно загрузить
 * @var bool $canDeleted Возможность удаления файлов
 * @var array $filesGridColuns Список колонок для отображения
 * @var string $uploadButtonHintText Текст описание для кнопки загрузки
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

    $deleteConfigMessage = Yii::t('system', 'Вы действительно хотите удалить текущий файл?');
    $this->registerJs(<<<JS
         $('.pjax-delete-link').on('click', function(e) {
             e.preventDefault();
             var deleteUrl = $(this).attr('delete-url');
             var pjaxContainer = $(this).attr('pjax-container');
             var result = confirm("$deleteConfigMessage");                                
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
                                                let string = "' . Yii::t('system', 'В процессе загрузки файлов возникли ошибки') . ':\r\n\r\n";
                                                $.each(response.errors, function(index, value){
                                                    string = string + value + "\r\n";
                                                });
                                            
                                                alert(string);
                                            }
                                        }',
                                        'fileuploadfail' => 'function(e, data) {
                                            alert("' . Yii::t('system', 'В процессе загрузки файлов возникли ошибки') . '");
                                        }',
                                    ],
                                ]);
                            } ?>
                        </div>
                    </div>

                    <?php if ($uploadButtonHintText) {
                        echo Html::tag('span', $uploadButtonHintText, ['class' => 'small text-muted mt-2 text-justify']);
                    } ?>
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
                    'id' => [
                        'visible' => isset($filesGridColuns['id']),
                    ],
                    'storage' => [
                        'visible' => isset($filesGridColuns['storage']),
                        'value' => fn($model) => AttachFileHelper::getStorageName(storageID: $model->storage)
                    ],
                    'name' => [
                        'visible' => isset($filesGridColuns['name']),
                        'format' => 'html',
                        'value' => function($model) {
                            return $model->name . Html::tag('span', '#'.Yii::$app->formatter->asShortSize($model['file_size']), ['class' => 'text-muted small ml-1']);
                        }
                    ],
                    'modelName' => [
                        'visible' => isset($filesGridColuns['modelName']),
                    ],
                    'modelKey' => [
                        'visible' => isset($filesGridColuns['modelKey']),
                    ],
                    'file_type' => [
                        'attribute' => 'file_type',
                        'value' => function($model) use($parentModel) {
                            return $parentModel->getAttachedFileTypeName($model->file_type);
                        }
                    ],
                    'file_hash' => [
                        'visible' => isset($filesGridColuns['file_hash']),
                    ],
                    'file_path' => [
                        'visible' => isset($filesGridColuns['file_path']),
                    ],
                    'file_size' => [
                        'visible' => isset($filesGridColuns['file_size']),
                    ],
                    'file_extension' => [
                        'visible' => isset($filesGridColuns['file_extension']),
                    ],
                    'file_mime' => [
                        'visible' => isset($filesGridColuns['file_mime']),
                    ],
                    'file_tags' => [
                        'visible' => isset($filesGridColuns['file_tags']),
                        'attribute' => 'file_tags',
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
                    'file_status' => [
                        'visible' => isset($filesGridColuns['file_status']),
                        'value' => fn($model) => AttachFileHelper::getFileStatus(status: $model->file_status)
                    ],
                    'file_version' => [
                        'visible' => isset($filesGridColuns['file_version']),
                    ],
                    [
                        'attribute' => 'created_at',
                        'value' => fn($model) => Yii::$app->getFormatter()->asDatetime($model->created_at)
                    ],
                    [
                        'attribute' => 'updated_at',
                        'value' => fn($model) => Yii::$app->getFormatter()->asDatetime($model->updated_at)
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
                                    Html::tag('i', '', ['class' => 'bi bi-download']),
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
                                    Html::tag('i', '', ['class' => 'bi bi-trash']),
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
<?php
    Pjax::end();