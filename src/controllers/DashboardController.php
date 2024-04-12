<?php

namespace app\controllers;

use app\base\BaseAR;
use Yii;
use yii\filters\AccessControl;

use app\base\BaseController;
use app\entities\report\ReportDataEntity;
use app\repositories\report\ReportRepository;
use app\search\report\{
    JobSearch,
    SendSearch
};
use app\helpers\RbacHelper;

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
        $reports = ReportRepository::getAllow($groups);
        $needSentData = (new SendSearch())->search([]);
        $queueTemplates = (new JobSearch(['onlyMain' => true]))->search([]);
        $sentData = ReportDataEntity::find()
            ->select(['report_id', 'COUNT(id) as id'])
            ->where([
                'report_id' => array_keys($reports),
                'created_uid' => Yii::$app->getUser()->id,
                'record_status' => BaseAR::RSTATUS_ACTIVE
            ])
            ->groupBy('report_id')
            ->asArray()
            ->all();

        return $this->render('index', [
            'reports' => $reports,
            'needSentData' => $needSentData,
            'queueTemplates' => $queueTemplates,
            'sentData' => $sentData
        ]);
    }
}
