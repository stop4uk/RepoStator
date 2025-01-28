<?php

namespace app\modules\reports\widgets\repeater;

use yii\base\Widget;
use yii\helpers\Json;

use app\components\base\BaseModel;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\widgets\repeater
 */
class Repeater extends Widget
{
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
     * @return string
     */
    public $buttonClasses = 'btn btn-primary btn-sm';

    /**
     * @return string
     */
    public $buttonDeleteClasses = 'btn btn-danger btn-sm w-100';

    /**
     * @return string
     */
    public $buttonName;

    /**
     * @return string
     */
    public $buttonDeleteName;

    /**
     * @return string
     */
    public $buttonPlaceBlock = 'col-md-2 offset-md-10';

    /**
     * @return string
     */
    public $buttonDeletePlaceBlock = 'col-md-1 text-center';

    /**
     * @return int|string|null
     */
    public $additionalField = null;

    public function run()
    {
        $view = $this->getView();
        RepeaterAsset::register($view);

        $data = Json::encode(['append' => $this->appendAction, 'remove' => $this->removeAction]);
        echo "<div class='ab-repeater'>";
        echo "<div class='list-area'>";
        echo "<input type='hidden' id='additionalField' value='" . $this->additionalField . "'/>";
        foreach($this->models as $k => $model){
            $content = $this->render($this->modelView, array_merge(['model' => $model, 'form' => $this->form, 'k' => $k, 'additionalField' => $this->additionalField], $this->additionalData));
            echo $this->render('repeater', [
                'content' => $content,
                'model' => $model,
                'k' => $k,
                'additionalField' => $this->additionalField,
                'buttonDeletePlaceBlock' => $this->buttonDeletePlaceBlock,
                'buttonDeleteName' => $this->buttonDeleteName,
                'buttonDeleteClasses' => $this->buttonDeleteClasses
            ]);
        }
        echo "</div>";
        echo "
                <div class='row'>    
                    <div class='" . $this->buttonPlaceBlock . "'>
                        <div class='ab-control d-grid gap-2 mb-2 mt-1'>
                            <a class='new-repeater " . $this->buttonClasses . "' href='javascript:;'>" . $this->buttonName . "</a>
                        </div>
                    </div>
                </div>
        </div>";

        $js = "new window.repeater($data)";
        $this->view->registerJs($js);
    }
}
