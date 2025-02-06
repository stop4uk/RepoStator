<?php

namespace app\actions;

use Yii;
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
        $session = Yii::$app->getSession();
        $sessionKey = $this->controller->module->id . $this->controller->id . '_search';
        $searchModel = new $this->searchModel($this->constructParams);
        $searchFilters = $this->controller->request->post();

        if ($this->controller->request->isGet) {
            $session->remove($sessionKey);
        }

        if ($this->controller->request->isPost) {
            if (
                $this->controller->request->getQueryParam('page') !== null
                && $session->has($sessionKey)
            ) {
                $searchFilters = $session->get($sessionKey);
            } else {
                $session->set($sessionKey, $this->controller->request->post());
            }
        }

        $dataProvider = $searchModel->search($searchFilters);

        return $this->controller->render('index', compact('searchModel', 'dataProvider'));
    }
}
