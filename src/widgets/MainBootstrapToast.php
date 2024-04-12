<?php

namespace app\widgets;

use Yii;
use yii\bootstrap5\{
    Html,
    Widget
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\widgets
 */
class MainBootstrapToast extends Widget
{
    /**
     * @var string|null
     */
    public $body = null;

    public function init()
    {
        parent::init();
        $this->initOptions();
    }

    public function run()
    {
        echo Html::beginTag('div', ['aria-live' => 'polite',  'aria-atomic' => 'true',  'class' => 'position-relative']);
            echo Html::beginTag('div', ['class' => 'toast-container position-absolute top-0 end-0 p-3']);
                echo Html::beginTag('div', $this->options);
                    echo Html::beginTag('div', ['class' => 'd-flex']);
                        echo Html::tag('div', $this->body, ['class' => 'toast-body']);
                        echo Html::button('', [
                            'type' => 'button',
                            'class' => 'me-2 m-auto btn-close',
                            'data-bs-dismiss' => 'toast',
                            'aria-label' => Yii::t('views', 'Закрыть')
                        ]);
                    echo Html::endTag('div');
                echo Html::endTag('div');
            echo Html::endTag('div');
        echo Html::endTag('div');

        $this->registerPlugin('toast');
        $this->view->registerJs('$(".toast").toast("show");');
    }

    protected function initOptions()
    {
        Html::addCssClass($this->options, ['widget' => 'toast']);

        if (!isset($this->options['role'])) {
            $this->options['role'] = 'alert';
        }

        if (!isset($this->options['aria']['live'])) {
            $this->options['aria'] = [
                'live' => 'assertive',
                'atomic' => 'true',
            ];
        }
    }
}
