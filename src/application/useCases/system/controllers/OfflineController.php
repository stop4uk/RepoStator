<?php

namespace app\useCases\system\controllers;

use Yii;
use yii\web\Controller;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers
 */
final class OfflineController extends Controller
{
    public function beforeAction($action): bool
    {
        $this->layout = 'clear';
        if ( !Yii::$app->settings->get('system', 'app_maintenance') ) {
            $this->goHome();
        }

        return parent::beforeAction($action);
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }
}
