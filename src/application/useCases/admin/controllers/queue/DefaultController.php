<?php

namespace app\useCases\admin\controllers\queue;

use yii\filters\AccessControl;

use app\actions\IndexAction;
use app\components\base\BaseController;
use app\useCases\admin\search\QueueSearch;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\admin\queue
 */
final class DefaultController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['admin.queue.system'],
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
                'searchModel' => QueueSearch::class
            ]
        ];
    }
}
