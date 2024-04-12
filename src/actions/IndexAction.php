<?php

namespace app\actions;

use yii\base\Action;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\actions
 */
final class IndexAction extends Action
{
    public $searchModel;
    public $constructParams = [];

    public function run(): string
    {
        $searchModel = new $this->searchModel($this->constructParams);
        $dataProvider = $searchModel->search($this->controller->request->post());

        return $this->controller->render('index', compact('searchModel', 'dataProvider'));
    }
}
