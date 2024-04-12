<?php

namespace app\controllers\reports;

use app\helpers\CommonHelper;
use app\repositories\group\GroupRepository;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Response;

use app\base\{
    BaseAR,
    BaseController,
};
use app\actions\{
    IndexAction,
    CreateEditAction,
    ViewAction,
    DeleteAction,
    EnableAction,
};
use app\services\report\ConstantruleService;
use app\entities\report\ReportConstantRuleEntity;
use app\repositories\report\{
    ConstantruleRepository,
    ConstantRepository,
    ReportRepository
};
use app\models\report\ConstantRuleModel;
use app\search\report\ConstantruleSearch;
use app\helpers\RbacHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\reports
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
                            'constantRule.list',
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            'constantRule.view.main',
                            'constantRule.view.group',
                            'constantRule.view.all',
                            'constantRule.view.main.delete',
                            'constantRule.view.group.delete',
                            'constantRule.view.all.delete'
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
                        'roles' => ['constantRule.create'],
                    ],
                    [
                        'actions' => ['edit', 'getselectdata'],
                        'allow' => true,
                        'roles' => [
                            'constantRule.edit.main',
                            'constantRule.edit.group',
                            'constantRule.edit.all',
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantruleRepository::get($this->request->get('id'));

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
                            'constantRule.delete.main',
                            'constantRule.delete.group',
                            'constantRule.delete.all',
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantruleRepository::get($this->request->get('id'));

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
                            'constantRule.enable.main',
                            'constantRule.enable.group',
                            'constantRule.enable.all',
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


        if ( $report_id ) {
            $reportInformation = ReportRepository::get($report_id);

            $reports  = [$report_id => $report_id];
            if ( $reportInformation && $reportInformation->groups_only) {
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
