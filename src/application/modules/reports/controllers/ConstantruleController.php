<?php

namespace app\modules\reports\controllers;

use app\actions\{CreateEditAction, DeleteAction, EnableAction, IndexAction, ViewAction};
use app\components\{base\BaseAR, base\BaseController};
use app\helpers\CommonHelper;
use app\modules\reports\{entities\ReportConstantRuleEntity,
    models\ConstantRuleModel,
    repositories\ConstantRepository,
    repositories\ConstantruleRepository,
    repositories\ReportRepository,
    search\ConstantruleSearch,
    services\ConstantruleService};
use app\modules\users\{components\rbac\items\Permissions, components\rbac\RbacHelper, repositories\GroupRepository};
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\controllers
 */
final class ConstantruleController extends BaseController
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
                            Permissions::CONSTANTRULE_LIST,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            Permissions::CONSTANTRULE_VIEW_MAIN,
                            Permissions::CONSTANTRULE_VIEW_GROUP,
                            Permissions::CONSTANTRULE_VIEW_ALL,
                            Permissions::CONSTANTRULE_VIEW_DELETE_MAIN,
                            Permissions::CONSTANTRULE_VIEW_DELETE_GROUP,
                            Permissions::CONSTANTRULE_VIEW_DELETE_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantruleRepository::get(
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
                        'actions' => ['create', 'getselectdata'],
                        'allow' => true,
                        'roles' => [Permissions::CONSTANTRULE_CREATE],
                    ],
                    [
                        'actions' => ['edit', 'getselectdata'],
                        'allow' => true,
                        'roles' => [
                            Permissions::CONSTANTRULE_EDIT_MAIN,
                            Permissions::CONSTANTRULE_EDIT_GROUP,
                            Permissions::CONSTANTRULE_EDIT_ALL,
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantruleRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = ConstantruleRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            Permissions::CONSTANTRULE_DELETE_MAIN,
                            Permissions::CONSTANTRULE_DELETE_GROUP,
                            Permissions::CONSTANTRULE_DELETE_ALL,
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantruleRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = ConstantruleRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            Permissions::CONSTANTRULE_ENABLE_MAIN,
                            Permissions::CONSTANTRULE_ENABLE_GROUP,
                            Permissions::CONSTANTRULE_ENABLE_ALL,
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantruleRepository::get(
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
        private readonly ConstantruleService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => ConstantruleSearch::class
            ],
            'create' => [
                'class' => CreateEditAction::class,
                'entity' => ReportConstantRuleEntity::class,
                'entityScenario' => BaseAR::SCENARIO_INSERT,
                'model' => ConstantRuleModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Reports.ConstantRule',
                'successMessage' => 'Правило сложения добавлено',
                'errorMessage' => 'При добавлении правила сложения возникли проблемы. Пожалуйста, обратитесь к администратору',
                'redirectUrl' => Url::to(['/reports/constantrule'])
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => ConstantruleRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => ConstantRuleModel::class,
                'exceptionMessage' => 'Запрашиваемое правило сложения не найдено, или недоступно'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'entityScenario' => BaseAR::SCENARIO_UPDATE,
                'repository' => ConstantruleRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => ConstantRuleModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Reports.ConstantRule',
                'refresh' => true,
                'successMessage' => 'Правило сложения обновлено',
                'errorMessage' => 'При обновлении правила сложения возникли проблемы. Пожалуйста, обратитесь к администратору',
                'exceptionMessage' => 'Запрашиваемое правило сложения не найдено, или недоступно',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => ConstantruleRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'fromEdit' => $this->request->get('fromEdit') ?? false,
                'redirectUrl' => Url::to(['/reports/constantrule/view', 'id' => $this->request->get('id')]),
                'errorMessage' => 'При удалении правила сложения возникли проблемы. Пожалуйста, обратитесь к администратору',
                'successMessage' => 'Правило сложения скрыто. Использовать его нельзя. Даже, если, оно присутствует в отчетах - считаться оно не будет',
                'exceptionMessage' => 'Запрашиваемое правило сложения не найдено, или недоступно'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => ConstantruleRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Запрашиваемое правило сложения не найдено, или недоступно'
            ],
        ];
    }

    public function actionGetselectdata(?int $report_id = null): array
    {
        $this->response->format = Response::FORMAT_JSON;

        $groupsAllow = RbacHelper::getAllowGroupsArray('constantRule.list.all');
        $groupsCanSent = GroupRepository::getAllBy(
            condition: ['id' => array_keys($groupsAllow), 'accept_send' => 1],
            asArray: true
        );


        if ($report_id) {
            $reportInformation = ReportRepository::get($report_id);

            $reports  = [$report_id => $report_id];
            if ($reportInformation && $reportInformation->groups_only) {
                $groups = array_filter($groupsCanSent, function($key) use ($reportInformation) {
                    return in_array($key, CommonHelper::explodeField($reportInformation->groups_only));
                }, ARRAY_FILTER_USE_KEY);
            }
        } else {
            $groups = $groupsCanSent;
            $reports = ReportRepository::getAllow(
                groups: $groups
            );
        }

        $contentConstants = ConstantRepository::getAllow(
            reports: $reports,
            groups: $groupsAllow
        );

        return compact('contentConstants', 'groups');
    }
}
