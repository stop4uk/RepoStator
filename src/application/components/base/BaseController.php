<?php

namespace app\components\base;

use Yii;
use yii\base\Exception;
use yii\web\Controller;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\componetns\base
 */
class BaseController extends Controller
{
    public function catchException(
        Exception $exception,
        bool $isPost = true
    ): array {
        if ($isPost) {
            Yii::$app
                ->getSession()
                ->setFlash('error', $exception->getMessage());
        }

        Yii::error($exception->getMessage());
        return [
            'status' => 'error',
            'message' => $exception->getMessage()
        ];
    }

    public function setMessage(
        string $type,
        string $message
    ): void {
        Yii::$app
            ->getSession()
            ->setFlash($type, $message);
    }
}
