<?php

namespace app\useCases\admin\controllers;

use yii\filters\AccessControl;

use app\actions\{
    IndexAction,
    ViewAction
};
use app\components\base\BaseController;
use app\useCases\system\entities\LogEntity;
use app\useCases\admin\search\LogSearch;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\admin
 */
final class LogsController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['admin.log'],
                    ],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => LogSearch::class
            ],
            'view' => [
                'class' => ViewAction::class,
                'entity' => LogEntity::class,
                'id' => $this->request->get('id'),
                'exceptionMessage' => 'Сообщение лога недоступно для просмотра'
            ],
        ];
    }
}
