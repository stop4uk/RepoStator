<?php

namespace app\controllers;

use yii\web\Controller;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers
 */
final class OfflineController extends Controller
{
    public $layout = 'clean';

    public function beforeAction($action): bool
    {
        if (!Yii::$app->settings->get('system', 'app_maintenance')) {
            $this->goHome();
        }

        return parent::beforeAction($action);
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
