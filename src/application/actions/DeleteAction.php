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
    public bool $fromEdit = false;
    public string|null $redirectUrl = null;

    /**
     * @throws NotFoundHttpException
     */
    public function run(): array|Response
    {
        $entity = match ((bool)$this->repository) {
            true => $this->repository::get($this->requestID),
            false => $this->entity::find()->where(['id' => $this->requestID])->limit(1)->one()
        };

        if (!$entity ) {
            throw new NotFoundHttpException(Yii::t('exceptions', $this->exceptionMessage));
        }

        try {
            $this->service->delete(
                entity: $entity,
                errorMessage: Yii::t('exceptions', $this->errorMessage)
            );

            if ($this->fromEdit) {
                $this->controller->setMessage('success', Yii::t('notifications', $this->successMessage));
                return $this->controller->redirect($this->redirectUrl);
            }

            $this->controller->response->format = Response::FORMAT_JSON;
            return [
                'status' => 'success',
                'message' => Yii::t('notifications', $this->successMessage)
            ];
        } catch (Exception $e) {
            return $this->controller->catchException($e, false);
        }
    }
}
