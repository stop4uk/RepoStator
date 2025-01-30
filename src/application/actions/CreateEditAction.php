<?php

namespace app\actions;

use Yii;
use yii\base\{
    Action,
    Exception
};
use yii\web\{
    NotFoundHttpException,
    Response,
    UploadedFile
};
use yii\bootstrap5\ActiveForm;

use app\components\base\BaseAR;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\actions
 */
final class CreateEditAction extends Action
{
    public $actionType = 'create';
    public $entity;
    public $entityScenario;
    public $repository;
    public $repositoryRelations = [];
    public $model;
    public $requestID;
    public $service;
    public $categoryForLog = null;
    public $refresh = false;
    public $redirectUrl;
    public $successMessage = null;
    public $errorMessage = null;
    public $exceptionMessage = null;

    public function run(): array|string|Response
    {
        switch ($this->actionType) {
            case 'create':
                $objectParams = [];
                if ($this->entityScenario) {
                    $objectParams['scenario'] = $this->entityScenario;
                }

                $entity = new $this->entity($objectParams);
                $actionView = 'create';
                break;
            case 'edit':
                $entity = match ((bool)$this->repository) {
                    true => $this->repository::get($this->requestID, $this->repositoryRelations),
                    false => $this->entity::find()->where(['id' => $this->requestID, 'record_status' => BaseAR::RSTATUS_ACTIVE])->limit(1)->one()
                };

                if (!$entity) {
                    throw new NotFoundHttpException(Yii::t('exceptions', $this->exceptionMessage));
                }

                if ($this->entityScenario) {
                    $entity->scenario = $this->entityScenario;
                }

                $actionView = 'edit';
                break;
        }


        $model = new $this->model($entity);

        if ($this->controller->request->isAjax && $model->load($this->controller->request->post())) {
            $this->controller->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load($this->controller->request->post()) && $model->validate()) {
            if (isset($model->uploadedFile)) {
                $model->uploadedFile = UploadedFile::getInstance($model, 'uploadedFile');
            }

            try {
                $this->service->save(
                    model: $model,
                    categoryForLog: $this->categoryForLog,
                    errorMessage: Yii::t('exceptions', $this->errorMessage)
                );

                $this->controller->setMessage('success', Yii::t('notifications', $this->successMessage));
                return match ($this->refresh) {
                    true => $this->controller->refresh(),
                    false => $this->controller->redirect($this->redirectUrl)
                };
            } catch (Exception $e) {
                $this->controller->catchException($e);
            }
        }

        return $this->controller->render($actionView, compact('model'));
    }
}
