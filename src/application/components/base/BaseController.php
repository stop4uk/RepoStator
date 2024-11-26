<?php

namespace app\components\base;

use Yii;
use yii\base\Exception;
use yii\web\{BadRequestHttpException, Controller, Response};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\base
 */
class BaseController extends Controller
{
    public function actionDownload(string $path): Response
    {
        $path = Yii::getAlias(base64_decode($path));

        if (file_exists($path)) {
            return $this->response->sendFile($path, basename($path), [
                'inline' => false
            ]);
        }

        throw new BadRequestHttpException(Yii::t('exceptions', 'Запрашиваемый файл не найден'));
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
