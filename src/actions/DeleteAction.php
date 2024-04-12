<?php

namespace app\actions;

use Yii;
use yii\base\{
    Action,
    Exception
};
use yii\web\{
    NotFoundHttpException,
    Response
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\actions
 */
final class DeleteAction extends Action
{
    public $entity;
    public $repository;
    public $requestID;
    public $service;
    public $errorMessage;
    public $successMessage;
    public $exceptionMessage;

    public function run(): array
    {
        $this->controller->response->format = Response::FORMAT_JSON;

        $entity = match((bool)$this->repository) {
            true => $this->repository::get($this->requestID),
            false => $this->entity::find()->where(['id' => $this->requestID])->limit(1)->one()
        };

        if ( !$entity ) {
            throw new NotFoundHttpException(Yii::t('exceptions', $this->exceptionMessage));
        }

        try {
            $this->service->delete(
                entity: $entity,
                errorMessage: Yii::t('exceptions', $this->errorMessage)
            );

            return [
                'status' => 'success',
                'message' => Yii::t('notifications', $this->successMessage)
            ];
        } catch (Exception $e) {
            return $this->controller->catchException($e, false);
        }
    }
}
