<?php

namespace app\modules\admin\controllers;

use yii\web\Response;
use yii\filters\AccessControl;
use yii\bootstrap5\ActiveForm;

use app\components\{
    base\BaseController,
    settings\SettingModel
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\admin\controllers
 */
final class SettingsController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin.setting'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new SettingModel();
        if ($this->request->isAjax && $model->load($this->request->post())) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($this->request->post()) && $model->validate()) {
            $model->saveSettings();
            $this->refresh();
        }

        return $this->render('index', compact('model'));
    }
}
