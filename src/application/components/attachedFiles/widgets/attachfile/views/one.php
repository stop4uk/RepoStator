<?php

use yii\helpers\Url;
use yii\data\ArrayDataProvider;
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
 * @var string $uploadButtonOptions Классы для кнопки загрузки
 * @var bool $showFileAsImage Отображать файл в виде фотографии
 * @var bool $isNewRecord когда загрузка осуществляется до сохранения привязанной записи в БД
 * @var string $uploadButtonHintText Текст описание для кнопки загрузки
 * @var string $sessionKey Ключ параметра в пользовательской сессии со списком файлов
 * @var \yii\db\BaseActiveRecord $parentModel Модель, к которой привязывается виджет
 * @var \yii\data\ArrayDataProvider $dataProvider Данные по уже загруженным и, находящимся в статусе ACTIVE файлам
 */

$fromSession = false;
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
    <div class="col-12">
        <?php
            if ($canAttached){
                foreach ($canAttached as $type => $params) {
                    $actionParams = [
                        'modelClass' => $parentModel::class,
                        'modelKey' => (string)$parentModel->{$parentModel->modelKey},
                        'modelType'  => $type,
                        'isNewRecord' => $isNewRecord
                    ];

                    echo FileUploadWidget::widget([
                        'model' => $uploadModel,
                        'attribute' => 'uploadFile',
                        'url' => Url::to(['attachfile', 'params' => base64_encode(serialize($actionParams))]),
                        'buttonName' => $uploadButtonTitle,
                        'buttonOptions' => 'btn btn-dark ' . $uploadButtonOptions,
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
                                    let string = "' . Yii::t('system', 'В процессе загрузки файла возникли ошибки') . ':\r\n\r\n";
                                    $.each(response.errors, function(index, value){
                                        string = string + value + "\r\n";
                                    });
                                            
                                    alert(string);
                                }
                            }',
                            'fileuploadfail' => 'function(e, data) {
                                alert("' . Yii::t('system', 'В процессе загрузки файла возникли ошибки') . '");
                            }',
                        ],
                    ]);
                }

                if ($uploadButtonHintText) {
                    echo Html::tag('span', $uploadButtonHintText, ['class' => 'small text-muted mt-2 text-justify']);
                }
            } else {
                if (
                    $dataProvider->getTotalCount() == 0
                    && $sessionFiles = Yii::$app->getSession()->get($sessionKey)
                ) {
                    $dataProvider = new ArrayDataProvider([
                        'allModels' => $sessionFiles,
                        'pagination' => [
                            'pageSize' => 5,
                        ],
                    ]);
                    $fromCache = true;
                }

                echo ListView::widget([
                    'dataProvider' => $dataProvider,
                    'layout' => '{items}',
                    'itemView' => 'one_item',
                    'options' => ['class' => 'row'],
                    'itemOptions' => ['class' => 'col-12 text-center'],
                    'viewParams' => [
                        'showFileAsImage' => $showFileAsImage,
                        'canDeleted' => $canDeleted,
                        'modelClass' => $parentModel::class ?? null,
                        'modelKey' => (string)$parentModel->{$parentModel->modelKey} ?? null,
                        'fromSession' => $fromSession
                    ]
                ]);
            }
        ?>
    </div>
</div>
<?php
    Pjax::end();