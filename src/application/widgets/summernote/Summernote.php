<?php

namespace app\widgets\summernote;

use yii\helpers\{
    ArrayHelper,
    Json
};
use yii\bootstrap5\Html;
use marqu3s\summernote\Summernote as BaseNote;

final class Summernote extends BaseNote
{
    public $clientOptions = [
        'toolbar' => [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['view', ['fullscreen', 'codeview', 'help']],
        ],
    ];

    public function init(): void
    {
        $this->getView()->registerCss('.note-toolbar{text-align: center; background: #fff}');
        parent::init();
    }

    public function run(): void
    {
        $this->registerAssets();

        echo $this->hasModel()
            ? Html::activeTextarea($this->model, $this->attribute, $this->options)
            : Html::textarea($this->name, $this->value, $this->options);

        if (empty($this->folder)) {
            $this->folder = "''";
        }

        $callbacks = $this->getExtendsParams('callbacks');
        $buttons = $this->getExtendsParams('buttons');
        $modules = $this->getExtendsParams('modules');

        $clientOptions = empty($this->clientOptions) ? null : Json::encode($this->clientOptions);

        $this->getView()->registerJs(
            '
            var params = ' .
            $clientOptions .
            ';' .
            (empty($callbacks) ? '' : 'params.callbacks = { ' . $callbacks . ' };') .
            (empty($buttons) ? '' : 'params.buttons = { ' . $buttons . ' };') .
            (empty($modules) ? '' : 'params.modules = { ' . $modules . ' };') .
            'jQuery( "#' .
            $this->options['id'] .
            '" ).summernote(params);
        '
        );
    }

    private function registerAssets(): void
    {
        $view = $this->getView();
        SummernoteAsset::register($view);
    }

    private function getExtendsParams($param)
    {
        $result = '';
        foreach (ArrayHelper::remove($this->clientOptions, $param, []) as $val => $key) {
            $result .= (empty($result) ? '' : ',') . $val . ': ' . $key;
        }

        return $result;
    }
}