<?php

namespace app\components\base;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\helpers\FileHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\base
 */
class BaseController extends Controller
{
    public function beforeAction($action): bool
    {
        if (
            $this->request->isGet
            && !$this->request->isPjax
        ) {
            $cacheFile = Yii::$app->getCache()->get('reportTempUpload_' . Yii::$app->getUser()->getId());
            if (
                $cacheFile
                && isset($cacheFile['fullPath'])
            ) {
                if (is_file($cacheFile['fullPath'])) {
                    FileHelper::unlink($cacheFile['fullPath']);
                }

                Yii::$app->getCache()->delete('reportTempUpload_' . Yii::$app->getUser()->getId());
            }
        }

        return parent::beforeAction($action);
    }

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
