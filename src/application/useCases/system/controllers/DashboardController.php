<?php

namespace app\useCases\system\controllers;

use yii\filters\AccessControl;

use app\components\base\BaseController;
use app\useCases\users\helpers\RbacHelper;
use app\useCases\reports\{
    repositories\ReportBaseRepository,
    search\JobSearch,
    search\SendSearch
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers
 */
final class DashboardController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $groups = RbacHelper::getAllowGroupsArray('report.list.all');
        $reports = ReportBaseRepository::getAllow($groups);
        $needSentData = (new SendSearch())->search([]);
        $queueTemplates = (new JobSearch(['onlyMain' => true]))->search([]);

        return $this->render('index', [
            'reports' => $reports,
            'needSentData' => $needSentData,
            'queueTemplates' => $queueTemplates,
        ]);
    }
}
