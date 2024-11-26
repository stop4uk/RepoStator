<?php

namespace app\useCases\system\controllers;

use Yii;
use yii\web\Controller;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers
 */
final class ErrorController extends Controller
{
    public function beforeAction($action): bool
    {
        $this->layout = 'clear';

        return parent::beforeAction($action);
    }

    public function actionFault(): string|bool
    {
        $exception = Yii::$app->errorHandler->exception;

        if ( $exception !== null ) {
            $statusCode = $exception->statusCode;
            $name = $exception->getName();
            $message = $exception->getMessage();
            $page = ( !in_array($statusCode, ['403', '404']) ) ? 'other' : $statusCode;

            return $this->render($page, compact('exception', 'statusCode', 'name', 'message'));
        }

        return false;
    }
}
