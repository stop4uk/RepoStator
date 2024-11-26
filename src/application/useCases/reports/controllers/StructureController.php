<?php

namespace app\useCases\reports\controllers;

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
    base\BaseController,
    base\BaseAR
};
use app\helpers\CommonHelper;
use app\useCases\reports\{
    entities\ReportStructureEntity,
    models\StructureModel,
    repositories\ConstantBaseRepository,
    repositories\ReportBaseRepository,
    repositories\StructureBaseRepository,
    services\StructureService,
    search\StructureSearch
};
use app\useCases\users\{
    repositories\group\GroupRepository,
    helpers\RbacHelper
};
use app\widgets\Repeater\actions\{
    AddAction,
    DeleteAction as RepeaterDeleteAction
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\reports
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
                            'structure.list',
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            'structure.view.main',
                            'structure.view.group',
                            'structure.view.all',
                            'structure.view.main.delete',
                            'structure.view.group.delete',
                            'structure.view.all.delete'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = StructureBaseRepository::get(
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
                        'roles' => ['structure.create'],
                    ],
                    [
                        'actions' => ['edit', 'getselectdata', 'addStructure', 'deleteStructure'],
                        'allow' => true,
                        'roles' => [
                            'structure.edit.main',
                            'structure.edit.group',
                            'structure.edit.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = StructureBaseRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = StructureBaseRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            'structure.delete.main',
                            'structure.delete.group',
                            'structure.delete.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = StructureBaseRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = StructureBaseRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            'structure.enable.main',
                            'structure.enable.group',
                            'structure.enable.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = StructureBaseRepository::get(
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
                'repository' => StructureBaseRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => StructureModel::class,
                'exceptionMessage' => 'Запрашиваемая структура передачи отчета не найдена, или недоступна'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'entityScenario' => BaseAR::SCENARIO_UPDATE,
                'repository' => StructureBaseRepository::class,
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
                'repository' => StructureBaseRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении структуры возникли проблемы. Пожалуйста, обратитесь к администратору',
                'successMessage' => 'Отчетная структура скрыта. Использовать ее нельзя. При этом, все ранее переденные через нее отчеты, будут отображаться',
                'exceptionMessage' => 'Запрашиваемая структура передачи отчета не найдена, или недоступна'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => StructureBaseRepository::class,
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

        $reportInformation = ReportBaseRepository::get($report_id);
        $groupsAllow = RbacHelper::getAllowGroupsArray('constantRule.list.all');
        $groupsCanSent = GroupRepository::getAllBy(
            condition: ['id' => array_keys($groupsAllow), 'accept_send' => 1],
            asArray: true
        );

        $groups = match ((bool)$reportInformation->groups_only) {
            true => array_filter($groupsCanSent, function($key) use ($reportInformation) {
                return in_array($key, CommonHelper::explodeField($reportInformation->groups_only));
            }, ARRAY_FILTER_USE_KEY),
            false => $groupsCanSent
        };

        $contentConstants = ConstantBaseRepository::getAllow(
            reports: [$report_id => $report_id],
            groups: $groupsAllow
        );

        return compact('contentConstants', 'groups');
    }
}
