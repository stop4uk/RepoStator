<?php

namespace app\modules\reports\widgets\repeater\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;

class DeleteAction extends Action
{
    public function run(): array
    {
        $id = Yii::$app->request->post('id');
        Yii::$app->response->format = Response::FORMAT_JSON;

        return ['status' => 1];
    }
}
