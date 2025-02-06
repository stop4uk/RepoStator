<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers
 */
final class ErrorController extends Controller
{
    public $layout = 'clean';
    public function actionFault(): string|bool
    {
        $exception = Yii::$app->getErrorHandler()->exception;

        if ($exception !== null) {
            $statusCode = $exception->statusCode ?? 999;
            $name = $exception->getName();
            $message = $exception->getMessage();
            $page = (!in_array($statusCode, ['403', '404']) ) ? 'other' : $statusCode;

            return $this->render($page, compact('exception', 'statusCode', 'name', 'message'));
        }

        return false;
    }
}
