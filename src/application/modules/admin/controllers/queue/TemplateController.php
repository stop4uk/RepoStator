<?php

namespace app\modules\admin\controllers\queue;

use yii\filters\AccessControl;

use app\components\base\BaseController;
use app\actions\IndexAction;
use app\modules\reports\search\JobSearch;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\admin\controllers\queue
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
