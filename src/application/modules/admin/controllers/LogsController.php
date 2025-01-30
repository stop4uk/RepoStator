<?php

namespace app\modules\admin\controllers;

use yii\filters\AccessControl;

use app\actions\{
    IndexAction,
    ViewAction
};
use app\components\{
    base\BaseController,
    entities\LogEntity
};
use app\modules\admin\search\LogSearch;
use app\modules\users\components\rbac\items\Permissions;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\admin\controllers
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
                        'roles' => [
                            Permissions::ADMIN_LOG
                        ],
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
