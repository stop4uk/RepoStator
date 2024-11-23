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

use app\base\BaseAR;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\actions
 */
final class EnableAction extends Action
{
    public $entity;
    public $repository;
    public $requestID;
    public $service;
    public $exceptionMessage;
    public $formControl;

    public function run(): Response
    {
        $entity = match((bool)$this->repository) {
            true => $this->repository::get($this->requestID, [], false),
            false => $this->entity::find()->where(['id' => $this->requestID])->limit(1)->one()
        };

        if ( !$entity ) {
            throw new NotFoundHttpException(Yii::t('exceptions', $this->exceptionMessage));
        }

        try {
            $entity->scenario = BaseAR::SCENARIO_CHANGE_RECORD_STATUS;
            $this->service->enable($entity);

            $url = ['edit', 'id' => $this->requestID];
            if ( $this->formControl ) {
                $url['form_control'] = true;
            }
            return $this->controller->redirect($url);
        } catch (Exception $e) {
            $this->controller->catchException($e);
        }
    }
}
