<?php

namespace app\modules\reports\controllers;

use yii\filters\AccessControl;
use yii\helpers\Url;

use app\actions\{
    CreateEditAction,
    DeleteAction,
    EnableAction,
    IndexAction,
    ViewAction
};
use app\components\base\BaseController;
use app\modules\reports\{
    entities\ReportEntity,
    models\ReportModel,
    repositories\ReportRepository,
    services\ReportService,
    search\ReportSearch
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\controllers
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
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => [
                            'report.list',
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            'report.view.main',
                            'report.view.group',
                            'report.view.all',
                            'report.view.main.delete',
                            'report.view.group.delete',
                            'report.view.all.delete',
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ReportRepository::get(
                                id: $this->request->get('id'),
                                active: false
                            );

                            return [
                                'created_uid' => $recordInformation->created_uid,
                                'created_gid' => $recordInformation->created_gid,
                                'record_status' => $recordInformation->record_status
                            ];
                        },
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['report.create'],
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => [
                            'report.edit.main',
                            'report.edit.group',
                            'report.edit.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ReportRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation->created_uid,
                                'created_gid' => $recordInformation->created_gid,
                                'record_status' => $recordInformation->record_status
                            ];
                        },
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            'report.delete.main',
                            'report.delete.group',
                            'report.delete.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ReportRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation->created_uid,
                                'created_gid' => $recordInformation->created_gid,
                                'record_status' => $recordInformation->record_status
                            ];
                        },
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            'report.enable.main',
                            'report.enable.group',
                            'report.enable.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ReportRepository::get(
                                id: $this->request->get('id'),
                                active: false
                            );

                            return [
                                'created_uid' => $recordInformation->created_uid,
                                'created_gid' => $recordInformation->created_gid,
                                'record_status' => $recordInformation->record_status
                            ];
                        },
                    ],
                ],
            ],
        ];
    }

    public function __construct(
        $id,
        $module,
        private readonly ReportService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => ReportSearch::class
            ],
            'create' => [
                'class' => CreateEditAction::class,
                'entity' => ReportEntity::class,
                'model' => ReportModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Reports.list',
                'successMessage' => 'Новый отчет успешно добавлен',
                'errorMessage' => 'При создании отчета произошла ошибка. Пожалуйста, обратитесь к администратору',
                'redirectUrl' => Url::to(['/reports'])
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => ReportRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => ReportModel::class,
                'exceptionMessage' => 'Запрашиваемый отчет не найден, или недоступен'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'repository' => ReportRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => ReportModel::class,
                'service' => $this->service,
                'refresh' => true,
                'categoryForLog' => 'Reports.list',
                'successMessage' => 'Данные отчета успешно изменены',
                'errorMessage' => 'При обновлении отчета произошла ошибка. Пожалуйста, обратитесь к администратору',
                'exceptionMessage' => 'Запрашиваемый отчет не найден, или недоступен',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => ReportRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении отчета возникли ошибки. Пожалуйста, обратитесь к администратору',
                'successMessage' => 'Отчет скрыт. Восстановление отчета доступно только администраторам',
                'exceptionMessage' => 'Запрашиваемый отчет не найден, или недоступен'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => ReportRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Запрашиваемый отчет не найден, или недоступен'
            ],
        ];
    }
}
