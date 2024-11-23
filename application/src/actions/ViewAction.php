<?php

namespace app\actions;

use Yii;
use yii\base\Action;
use yii\web\NotFoundHttpException;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\actions
 */
final class ViewAction extends Action
{
    public $entity;
    public $repository;
    public $repositoryRelations = [];
    public $requestID;
    public $model;
    public $exceptionMessage;

    public function run(): string
    {
        $entity = match((bool)$this->repository) {
            true => $this->repository::get($this->requestID, $this->repositoryRelations, false),
            false => $this->entity::find()->where(['id' => $this->requestID])->limit(1)->one()
        };

        if ( !$entity ) {
            throw new NotFoundHttpException(Yii::t('exceptions', $this->exceptionMessage));
        }


        if ( $this->model ) {
            $model = new $this->model($entity);
            $returnParams = compact('model');
        } else {
            $returnParams = compact('entity');
        }

        return $this->controller->render('view', $returnParams);
    }
}
