<?php

namespace app\useCases\admin\controllers\queue;

use yii\filters\AccessControl;

use app\actions\IndexAction;
use app\components\base\BaseController;
use app\useCases\report\search\JobSearch;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\admin\queue
 */
final class TemplateController extends BaseController
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
                        'roles' => ['admin.queue.template.list'],
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
                'searchModel' => JobSearch::class,
                'constructParams' => ['onlyMain' => true]
            ]
        ];
    }
}
