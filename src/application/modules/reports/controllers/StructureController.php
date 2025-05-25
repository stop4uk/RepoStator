<?php

namespace app\modules\reports\controllers;

use yii\web\Response;
use yii\filters\AccessControl;
use yii\helpers\Url;

use app\actions\{
    CreateEditAction,
    DeleteAction,
    EnableAction,
    IndexAction,
    ViewAction
};
use app\components\{
    base\BaseAR,
    base\BaseController
};
use app\widgets\repeater\actions\{
    AddAction,
    DeleteAction as RepeaterDeleteAction
};
use app\modules\reports\{
    entities\ReportStructureEntity,
    models\StructureModel,
    repositories\ConstantRepository,
    repositories\ReportRepository,
    repositories\StructureRepository,
    search\StructureSearch,
    services\StructureService
};
use app\modules\users\{
    components\rbac\items\Permissions,
    components\rbac\RbacHelper,
    repositories\GroupRepository
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\controllers
 */
final class StructureController extends BaseController
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
                            Permissions::STRUCTURE_LIST,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            Permissions::STRUCTURE_VIEW_MAIN,
                            Permissions::STRUCTURE_VIEW_GROUP,
                            Permissions::STRUCTURE_VIEW_ALL,
                            Permissions::STRUCTURE_VIEW_DELETE_MAIN,
                            Permissions::STRUCTURE_VIEW_DELETE_GROUP,
                            Permissions::STRUCTURE_VIEW_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = StructureRepository::get(
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
                        'actions' => ['create', 'getselectdata', 'addStructure', 'deleteStructure'],
                        'allow' => true,
                        'roles' => [
                            Permissions::STRUCTURE_CREATE
                        ],
                    ],
                    [
                        'actions' => ['edit', 'getselectdata', 'addStructure', 'deleteStructure'],
                        'allow' => true,
                        'roles' => [
                            Permissions::STRUCTURE_EDIT_MAIN,
                            Permissions::STRUCTURE_EDIT_GROUP,
                            Permissions::STRUCTURE_EDIT_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = StructureRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = StructureRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            Permissions::STRUCTURE_DELETE_MAIN,
                            Permissions::STRUCTURE_DELETE_GROUP,
                            Permissions::STRUCTURE_DELETE_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = StructureRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = StructureRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            Permissions::STRUCTURE_ENABLE_MAIN,
                            Permissions::STRUCTURE_ENABLE_GROUP,
                            Permissions::STRUCTURE_ENABLE_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = StructureRepository::get(
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
        private readonly StructureService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => StructureSearch::class
            ],
            'create' => [
                'class' => CreateEditAction::class,
                'entity' => ReportStructureEntity::class,
                'entityScenario' => BaseAR::SCENARIO_INSERT,
                'model' => StructureModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Reports.Structure',
                'successMessage' => 'Отчетная структура добавлена',
                'errorMessage' => 'При добавлении структуры возникли проблемы. Пожалуйста, обратитесь к администратору',
                'redirectUrl' => Url::to(['/reports/structure'])
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => StructureRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => StructureModel::class,
                'exceptionMessage' => 'Запрашиваемая структура передачи отчета не найдена, или недоступна'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'entityScenario' => BaseAR::SCENARIO_UPDATE,
                'repository' => StructureRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => StructureModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Reports.Structure',
                'refresh' => true,
                'successMessage' => 'Отчетная структура обновлена',
                'errorMessage' => 'При обновлении структуры возникли проблемы. Пожалуйста, обратитесь к администратору',
                'exceptionMessage' => 'Запрашиваемая структура передачи отчета не найдена, или недоступна',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => StructureRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'fromEdit' => $this->request->get('fromEdit') ?? false,
                'redirectUrl' => Url::to(['/reports/structure/view', 'id' => $this->request->get('id')]),
                'errorMessage' => 'При удалении структуры возникли проблемы. Пожалуйста, обратитесь к администратору',
                'successMessage' => 'Отчетная структура скрыта. Использовать ее нельзя. При этом, все ранее переденные через нее отчеты, будут отображаться',
                'exceptionMessage' => 'Запрашиваемая структура передачи отчета не найдена, или недоступна'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => StructureRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Запрашиваемая структура передачи отчета не найдена, или недоступна'
            ],
            'addStructure' => [
                'class' => AddAction::class,
                'model' => StructureModel::class,
                'constructClass' => ReportStructureEntity::class,
                'contentPath' => '@resources/views/reports/structure/_partial/form_generateItems',
            ],
            'deleteStructure' => [
                'class' => RepeaterDeleteAction::class,
            ],
        ];
    }

    public function actionGetselectdata(int $report_id): array
    {
        $this->response->format = Response::FORMAT_JSON;

        $reportInformation = ReportRepository::get($report_id);
        $groupsAllow = RbacHelper::getAllowGroupsArray('constantRule.list.all');
        $groupsCanSent = GroupRepository::getAllBy(
            condition: ['id' => array_keys($groupsAllow), 'accept_send' => 1],
            asArray: true
        );

        $groups = match ((bool)$reportInformation->groups_only) {
            true => array_filter($groupsCanSent, function($key) use ($reportInformation) {
                return (
                    (!is_array($reportInformation->groups_only) && $reportInformation->groups_only == $key)
                    || in_array($key, $reportInformation->groups_only)
                );
            }, ARRAY_FILTER_USE_KEY),
            false => $groupsCanSent
        };

        $contentConstants = ConstantRepository::getAllow(
            reports: [$report_id => $report_id],
            groups: $groupsAllow
        );

        return compact('contentConstants', 'groups');
    }
}
