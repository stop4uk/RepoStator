<?php

namespace app\useCases\reports\controllers;

use Yii;
use yii\web\Response;
use yii\filters\AccessControl;
use yii\helpers\{
    ArrayHelper,
    FileHelper,
    Url
};

use app\actions\{
    CreateEditAction,
    DeleteAction,
    EnableAction,
    IndexAction,
    ViewAction
};
use app\components\{
    base\BaseController,
    base\BaseAR,
    attachedFiles\AttachFileActionsTrait
};
use app\useCases\reports\{
    entities\ReportFormTemplateEntity,
    models\TemplateModel,
    repositories\ConstantRepository,
    repositories\ConstantruleRepository,
    repositories\TemplateRepository,
    services\TemplateService,
    search\TemplateSearch
};
use app\useCases\users\helpers\RbacHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\reports
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
                            'template.list',
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            'template.view.main',
                            'template.view.group',
                            'template.view.all',
                            'template.view.main.delete',
                            'template.view.group.delete',
                            'template.view.all.delete'
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
                        'roles' => ['template.create'],
                    ],
                    [
                        'actions' => ['edit', 'getselectdata'],
                        'allow' => true,
                        'roles' => [
                            'template.edit.main',
                            'template.edit.group',
                            'template.edit.all'
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
                            'template.delete.main',
                            'template.delete.group',
                            'template.delete.all'
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
                            'template.enable.main',
                            'template.enable.group',
                            'template.enable.all'
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
            $cacheFile = Yii::$app->getCache()->get('reportTempUpload_' . Yii::$app->getUser()->getId());
            if (
                $cacheFile
                && isset($cacheFile['fullPath'])
            ) {
                if (is_file($cacheFile['fullPath'])) {
                    FileHelper::unlink($cacheFile['fullPath']);
                }

                Yii::$app->getCache()->delete('reportTempUpload_' . Yii::$app->getUser()->getId());
            }
        }

        return parent::beforeAction($action);
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
