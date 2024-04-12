<?php

namespace app\widgets\Repeater\actions;

use Yii;
use yii\base\Action;

use app\base\BaseAR;
use app\base\BaseModel;

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
        if ( $this->constructClass ) {
            $model = new $this->model(new $this->constructClass);
        } else {
            $model = new $this->model();
        }

        $this->controller->viewPath = dirname(__DIR__) . '/views';
        $id = Yii::$app->request->post('id');
        $additionalField = Yii::$app->request->post('additionalField');
        $buttonDeleteData = Yii::$app->request->post('buttonDeleteData');

        return $this->controller->renderAjax('repeater', [
            'k' => $id,
            'model' => $model,
            'contentPath' => $this->contentPath,
            'additionalField' => $additionalField,
            'buttonDeletePlaceBlock' => $buttonDeleteData['buttonDeletePlaceBlock'],
            'buttonDeleteName' => $buttonDeleteData['buttonDeleteName'],
            'buttonDeleteClasses' => $buttonDeleteData['buttonDeleteClasses']
        ]);
    }
}
