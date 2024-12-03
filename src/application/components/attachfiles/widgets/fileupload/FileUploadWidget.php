<?php

namespace common\attachfiles\widgets\fileupload;

use yii\bootstrap\Html;
use dosamigos\fileupload\FileUpload as BaseFileUploadWidget;

final class FileUploadWidget extends BaseFileUploadWidget
{
    public string|null $buttonName = null;
    public string|null $buttonOptions = null;

    public function run()
    {
        $this->view->registerCss('
            .fileinput-button {
                display: block!important;
                text-decoration:none!important,
                color:#686c71!important;
                background-color: inherit!important
            }
            
            .fileinput-button:hover {
                color:#16181b!important;
                background-color:#f8f9fa!important;
            }
            
        ');

        $input = $this->hasModel()
            ? Html::activeFileInput($this->model, $this->attribute, $this->options)
            : Html::fileInput($this->name, $this->value, $this->options);

        echo $this->useDefaultButton
            ? $this->render($this->uploadButtonTemplateView, [
                'input' => $input,
                'buttonName' => $this->buttonName,
                'buttonOptions' => $this->buttonOptions
            ])
            : $input;

        $this->registerClientScript();
    }
}