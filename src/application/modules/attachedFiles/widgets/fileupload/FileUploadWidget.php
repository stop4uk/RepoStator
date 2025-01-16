<?php

namespace widgets\fileupload;

use dosamigos\fileupload\{FileUpload as BaseFileUploadWidget, FileUploadPlusAsset};
use widgets\fileupload\assets\FileUploadAsset;
use yii\bootstrap5\Html;
use yii\helpers\Json;

final class FileUploadWidget extends BaseFileUploadWidget
{
    /**
     * @var string|null
     */
    public $buttonName;
    /**
     * @var string|null
     */
    public $buttonOptions;

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

    public function registerClientScript()
    {
        $view = $this->getView();

        if($this->plus) {
            FileUploadPlusAsset::register($view);
        } else {
            FileUploadAsset::register($view);
        }

        $options = Json::encode($this->clientOptions);
        $id = $this->options['id'];

        $js[] = ";jQuery('#$id').fileupload($options);";
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
        }
        $view->registerJs(implode("\n", $js));
    }
}