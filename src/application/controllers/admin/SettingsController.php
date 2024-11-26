<?php

namespace app\controllers\admin;

use app\components\base\BaseController;
use app\models\SettingModel;
use yii\bootstrap5\ActiveForm;
use yii\filters\AccessControl;
use yii\web\Response;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\admin
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
        if ( $this->request->isAjax && $model->load($this->request->post()) ) {
            $this->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ( $model->load($this->request->post()) && $model->validate() ) {
            $model->saveSettings();
            $this->refresh();
        }

        return $this->render('index', compact('model'));
    }
}
