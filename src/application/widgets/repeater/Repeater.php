<?php

namespace app\widgets\repeater;

use Yii;
use yii\base\Widget;
use yii\helpers\Json;

use app\components\base\BaseModel;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\widgets\repeater
 */
class Repeater extends Widget
{
    const TEMPLATE_DIV = 'div';
    const TEMPLATE_TABLE = 'table';

    public string $template = self::TEMPLATE_DIV;

    /**
     * @var int|string
     */
    public $widgetID;

    /**
     * @var array
     */
    public $models;

    /**
     * @var string
     */
    public $modelView;

    /**
     * @var string
     */
    public $appendAction;

    /**
     * @var string
     */
    public $removeAction;

    /**
     * @var BaseModel
     */
    public $form;

    /**
     * @var array
     */
    public $additionalData = [];

    /**
     * @var mixed
     */
    public $additionalInformation = null;

    /**
     * @var string
     */
    public $buttonClasses = 'btn btn-primary btn-sm';

    /**
     * @var string
     */
    public $buttonDeleteClasses = 'btn btn-danger btn-sm w-100';

    /**
     * @var string
     */
    public $buttonName;

    /**
     * @var string
     */
    public string $buttonDeleteName = '<i class="bi bi-trash"></i>';

    /**
     * @var string
     */
    public string $buttonDeletePlaceBlock = 'col-md-1 text-center';

    /**
     * @var boolean
     */
    public bool $buttonDeleteShowLabelBefore = true;

    /**
     * @var int|string|null
     */
    public $additionalField = null;

    public function init(): void
    {
        if (!$this->widgetID) {
            $this->widgetID = Yii::$app->getSecurity()->generateRandomString(6);
        }

        parent::init();
    }

    public function run(): void
    {
        $view = $this->getView();
        RepeaterAsset::register($view);

        $data = Json::encode([
            'append' => $this->appendAction,
            'remove' => $this->removeAction,
            'template' => $this->template,
            'widgetID' => $this->widgetID,
            'additionalInformation' => $this->additionalInformation,
            'buttonDeleteShowLabelBefore' => $this->buttonDeleteShowLabelBefore
        ]);

        match($this->template) {
            self::TEMPLATE_DIV => $this->runByDiv(),
            self::TEMPLATE_TABLE => $this->runByTable()
        };

        $view->registerJs("repeater($data)");
    }

    private function runByDiv(): void
    {
        echo "<div class='ab-repeater_" . $this->widgetID . "'>";
        echo "<div class='list-area'>";
        foreach($this->models as $k => $model) {
            $content = $this->render($this->modelView, array_merge([
                'k' => $k,
                'model' => $model,
                'form' => $this->form,
                'widgetID' => $this->widgetID,
                'additionalField' => $this->additionalField,
                'additionalInformation' => $this->additionalInformation,

            ], $this->additionalData));

            echo $this->render('repeater_div', [
                'k' => $k,
                'model' => $model,
                'content' => $content,
                'widgetID' => $this->widgetID,
                'additionalInformation' => $this->additionalInformation,
                'buttonDeletePlaceBlock' => $this->buttonDeletePlaceBlock,
                'buttonDeleteName' => $this->buttonDeleteName,
                'buttonDeleteClasses' => $this->buttonDeleteClasses,
                'buttonDeleteShowLabelBefore' => $this->buttonDeleteShowLabelBefore
            ]);
        }
        echo "</div>";

        echo "
                <div class='ab-control d-flex justify-content-center mt-1 mb-1'>
                    <button type='button' id='new_repeater_{$this->widgetID}' class='btn btn-dark new-repeater_{$this->widgetID}'>
                        <i class='bi bi-plus'></i>
                    </button>
                </div>
            ";
        echo "</div>";
    }

    private function runByTable(): void
    {
        foreach($this->models as $k => $model) {
            $content = $this->render($this->modelView, array_merge([
                'additionalInformation' => $this->additionalInformation,
                'widgetID' => $this->widgetID,
                'template' => $this->template,
                'model' => $model,
                'form' => $this->form,
                'k' => $k,
                'additionalField' => $this->additionalField,
                'buttonDeletePlaceBlock' => $this->buttonDeletePlaceBlock,
                'buttonDeleteName' => $this->buttonDeleteName,
                'buttonDeleteClasses' => $this->buttonDeleteClasses,
                'buttonDeleteShowLabelBefore' => $this->buttonDeleteShowLabelBefore
            ], $this->additionalData));

            echo $this->render('repeater_table', [
                'additionalInformation' => $this->additionalInformation,
                'widgetID' => $this->widgetID,
                'template' => $this->template,
                'content' => $content,
                'model' => $model,
                'k' => $k,
            ]);
        }
    }
}
