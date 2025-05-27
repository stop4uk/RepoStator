<?php

namespace app\actions;

use yii\base\Action;

use app\helpers\CommonHelper;

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
        $searchFilters = CommonHelper::formFilter(
            searchModel: $searchModel,
            request: $this->controller->request,
            sessionName: $this->controller->module->id . $this->controller->id . '_search'
        );

        $dataProvider = $searchModel->search($searchFilters);
        return $this->controller->render('index', compact('searchModel', 'dataProvider'));
    }
}
