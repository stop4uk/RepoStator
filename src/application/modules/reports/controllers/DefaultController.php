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
use app\modules\users\components\rbac\items\Permissions;

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
                            Permissions::REPORT_LIST,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            Permissions::REPORT_VIEW_MAIN,
                            Permissions::REPORT_VIEW_GROUP,
                            Permissions::REPORT_VIEW_ALL,
                            Permissions::REPORT_VIEW_DELETE_MAIN,
                            Permissions::REPORT_VIEW_DELETE_GROUP,
                            Permissions::REPORT_VIEW_DELETE_ALL,
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
                        'roles' => [Permissions::REPORT_CREATE],
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => [
                            Permissions::REPORT_EDIT_MAIN,
                            Permissions::REPORT_EDIT_GROUP,
                            Permissions::REPORT_EDIT_ALL
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
                            Permissions::REPORT_DELETE_MAIN,
                            Permissions::REPORT_DELETE_GROUP,
                            Permissions::REPORT_DELETE_ALL
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
                            Permissions::REPORT_ENABLE_MAIN,
                            Permissions::REPORT_ENABLE_GROUP,
                            Permissions::REPORT_ENABLE_ALL
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
                'fromEdit' => $this->request->get('fromEdit') ?? false,
                'redirectUrl' => Url::to(['/reports/view', 'id' => $this->request->get('id')]),
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
