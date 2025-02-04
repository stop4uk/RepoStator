<?php

namespace app\widgets\repeater\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\widgets\repeater\actions
 */
class DeleteAction extends Action
{
    public function run(): array
    {
        $id = Yii::$app->request->post('id');
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['status' => 1];
    }
}
