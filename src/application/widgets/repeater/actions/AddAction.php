<?php

namespace app\widgets\repeater\actions;

use Yii;
use yii\base\Action;

use app\components\base\{
    BaseAR,
    BaseModel
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\widgets\repeater\actions
 */
class AddAction extends Action
{
    /**
     * @var BaseModel $model
     */
    public $model;

    /**
     * @var BaseAR $constructClass
     */
    public $constructClass;

    /**
     * @var string $contentPath
     */
    public $contentPath;

    /**
     * @return string
     */
    public $buttonDeleteName;

    /**
     * @return string
     */
    public $buttonDeletePlaceBlock = 'col-md-1 text-center';

    /**
     * @return string
     */
    public $buttonDeleteClasses = 'btn btn-danger btn-sm w-100';

    public function run()
    {
        $this->controller->viewPath = dirname(__DIR__) . '/views';
        $model = is_array($this->model)
            ? $this->model
            : ($this->constructClass
                ? new $this->model(new $this->constructClass)
                : new $this->model()
            );


        $id = Yii::$app->request->post('id');
        $widgetID = Yii::$app->request->post('widgetID');
        $template = Yii::$app->request->post('template') ?: 'div';
        $buttonDeleteData = Yii::$app->request->post('buttonDeleteData');
        $additionalField = Yii::$app->request->post('additionalField');
        $additionalInformation = Yii::$app->request->post('additionalInformation');
        $buttonDeleteShowLabelBefore = Yii::$app->request->post('buttonDeleteShowLabelBefore');

        return $this->controller->renderAjax('repeater_' . $template, [
            'k' => $id,
            'model' => $model,
            'widgetID' => $widgetID,
            'template' => $template,
            'contentPath' => $this->contentPath,
            'additionalField' => $additionalField,
            'additionalInformation' => $additionalInformation,
            'buttonDeletePlaceBlock' => $buttonDeleteData['buttonDeletePlaceBlock'],
            'buttonDeleteName' => $buttonDeleteData['buttonDeleteName'],
            'buttonDeleteClasses' => $buttonDeleteData['buttonDeleteClasses'],
            'buttonDeleteShowLabelBefore' => $buttonDeleteShowLabelBefore
        ]);
    }
}
