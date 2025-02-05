<?php

namespace app\modules\reports\controllers;

use yii\filters\AccessControl;

use app\components\{
    base\BaseController,
    attachedFiles\AttachFileActionsTrait,
};
use app\modules\reports\{
    repositories\ReportRepository,
    search\JobSearch,
    search\SendSearch
};
use app\modules\users\components\rbac\RbacHelper;


/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\controllers
 */
final class DashboardController extends BaseController
{
    use AttachFileActionsTrait;

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

    public function actionIndex(): string
    {
        $groups = RbacHelper::getAllowGroupsArray('report.list.all');
        $reports = ReportRepository::getAllow($groups);
        $needSentData = (new SendSearch())->search([]);
        $queueTemplates = (new JobSearch(['onlyMine' => true]))->search([]);

        return $this->render('index', [
            'reports' => $reports,
            'needSentData' => $needSentData,
            'queueTemplates' => $queueTemplates,
        ]);
    }
}
