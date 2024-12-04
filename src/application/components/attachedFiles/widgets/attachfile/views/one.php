<?php

use yii\helpers\Url;
use yii\widgets\{
    Pjax,
    ListView
};
use yii\bootstrap5\Html;

use app\components\attachedFiles\{
    AttachFileUploadForm,
    widgets\fileupload\FileUploadWidget
};

/**
 * @var array $canAttached Возможность загрузить фото
 * @var bool $canDeleted Возможность удаления файлов
 * @var string $uploadButtonTitle Текст кнопки загрузки файла
 * @var bool $showFileAsImage Отображать файл в виде фотографии
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

    $deleteConfigMessage = Yii::t('system', 'Вы действительно хотите удалить файл?');
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

<div class="row">
    <div class="12">
        <?php
            if ($canAttached){
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
                        'buttonName' => $uploadButtonTitle,
                        'buttonOptions' => 'btn btn-dark',
                        'options' => [
                            'id' => 'fileUpWidget',
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
                                alert("' . Yii::t('system', 'В процессе загрузки файла произошла ошибка. Пожалуйста, обратитесь к администратору') . '");
                            }',
                        ],
                    ]);
                }
            } else {
                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '{items}',
                    'itemView' => 'one_item',
                    'options' => ['class' => 'row'],
                    'itemOptions' => ['class' => 'col-12 text-center'],
                    'viewParams' => [
                        'showFileAsImage' => $showFileAsImage,
                        'canDeleted' => $canDeleted,
                        'modelClass' => $parentModel::class,
                        'modelKey' => (string)$parentModel->{$parentModel->modelKey},
                    ]
                ]);
            }
        ?>
    </div>
</div>
<?php
    Pjax::end();