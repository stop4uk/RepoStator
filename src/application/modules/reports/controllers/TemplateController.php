<?php

namespace app\modules\reports\controllers;

use app\actions\{CreateEditAction, DeleteAction, EnableAction, IndexAction, ViewAction};
use app\components\{attachedFiles\AttachFileActionsTrait, base\BaseAR, base\BaseController};
use app\helpers\CommonHelper;
use app\modules\reports\{entities\ReportFormTemplateEntity,
    models\TemplateModel,
    repositories\ConstantRepository,
    repositories\ConstantruleRepository,
    repositories\TemplateRepository,
    search\TemplateSearch,
    services\TemplateService};
use app\modules\users\{components\rbac\items\Permissions, components\rbac\RbacHelper};
use Yii;
use yii\filters\AccessControl;
use yii\helpers\{ArrayHelper, FileHelper, Url};
use yii\web\Response;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\controllers
 */
final class TemplateController extends BaseController
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
                        'actions' => ['index'],
                        'roles' => [
                            Permissions::TEMPLATE_CREATE,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            Permissions::TEMPLATE_VIEW_MAIN,
                            Permissions::TEMPLATE_VIEW_GROUP,
                            Permissions::TEMPLATE_VIEW_ALL,
                            Permissions::TEMPLATE_VIEW_DELETE_MAIN,
                            Permissions::TEMPLATE_VIEW_DELETE_GROUP,
                            Permissions::TEMPLATE_VIEW_DELETE_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = TemplateRepository::get(
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
                        'actions' => ['create', 'getselectdata', 'attachfile', 'detachfile', 'getfile'],
                        'allow' => true,
                        'roles' => [
                            Permissions::TEMPLATE_CREATE
                        ],
                    ],
                    [
                        'actions' => ['edit', 'getselectdata'],
                        'allow' => true,
                        'roles' => [
                            Permissions::TEMPLATE_EDIT_MAIN,
                            Permissions::TEMPLATE_EDIT_GROUP,
                            Permissions::TEMPLATE_EDIT_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = TemplateRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = TemplateRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            Permissions::TEMPLATE_DELETE_MAIN,
                            Permissions::TEMPLATE_DELETE_GROUP,
                            Permissions::TEMPLATE_DELETE_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = TemplateRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = TemplateRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            Permissions::TEMPLATE_ENABLE_MAIN,
                            Permissions::TEMPLATE_ENABLE_GROUP,
                            Permissions::TEMPLATE_ENABLE_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = TemplateRepository::get(
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
        private readonly TemplateService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function beforeAction($action): bool
    {
        if (
            $action->id == 'create'
            && $this->request->isGet
            && !$this->request->isPjax
        ) {
            $session = Yii::$app->getSession();
            $sessionKey = env('YII_UPLOADS_TEMPORARY_KEY', 'tmpUploadSession') . CommonHelper::getUserID();
            $sessionFiles = $session->get($sessionKey);

            if ($sessionFiles) {
                foreach ($sessionFiles as $file) {
                    if (
                        isset($file['fullPath'])
                        && is_file($file['fullPath'])
                    ) {
                        FileHelper::unlink($file['fullPath']);
                    }
                }
                $session->remove($sessionKey);
            }
        }

        return parent::beforeAction($action); // TODO: Change the autogenerated stub
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => TemplateSearch::class
            ],
            'create' => [
                'class' => CreateEditAction::class,
                'entity' => ReportFormTemplateEntity::class,
                'entityScenario' => BaseAR::SCENARIO_INSERT,
                'model' => TemplateModel::class,
                'service' => $this->service,
                'successMessage' => 'Новый шаблон формирования отчета добавлен',
                'redirectUrl' => Url::to(['/reports/template'])
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => TemplateRepository::class,
                'repositoryRelations' => ['report'],
                'requestID' => $this->request->get('id'),
                'model' => TemplateModel::class,
                'exceptionMessage' => 'Запрашиваемый шаблон формирования отчета не найден, или недоступен'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'entityScenario' => BaseAR::SCENARIO_UPDATE,
                'repository' => TemplateRepository::class,
                'repositoryRelations' => ['report'],
                'requestID' => $this->request->get('id'),
                'model' => TemplateModel::class,
                'service' => $this->service,
                'refresh' => true,
                'successMessage' => 'Шаблон формирования отчета обновлен',
                'exceptionMessage' => 'Запрашиваемый шаблон формирования отчета не найден, или недоступен',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => TemplateRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении шаблона формирования возникли проблемы. Пожалуйста, обратитесь к администратору',
                'successMessage' => 'Шаблон формирования отчета скрыт. Использовать его нельзя. Однако, ранее сформированные отчеты по данному шаблону срок хранения которых не вышел - все еще доступны',
                'exceptionMessage' => 'Запрашиваемый шаблон формирования отчета не найден, или недоступен'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => TemplateRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Запрашиваемый шаблон формирования отчета не найден, или недоступен'
            ]
        ];
    }

    public function actionGetselectdata(int $report_id): array
    {
        $this->response->format = Response::FORMAT_JSON;

        $groups = RbacHelper::getAllowGroupsArray('template.list.all');
        $mergeConstantAndRules = ArrayHelper::merge(
            ConstantRepository::getAllow(
                reports: [$report_id => $report_id],
                groups: $groups
            ),
            ConstantruleRepository::getAllow(
                reports: [$report_id => $report_id],
                groups: $groups
            )
        );

        return compact('groups', 'mergeConstantAndRules');
    }
}
