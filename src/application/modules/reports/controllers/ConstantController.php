<?php

namespace app\modules\reports\controllers;

use Yii;
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
use app\modules\reports\{
    entities\ReportConstantEntity,
    models\ConstantModel,
    repositories\ConstantRepository,
    services\ConstantService,
    search\ConstantSearch,
    widgets\repeater\actions\AddAction,
    widgets\repeater\actions\DeleteAction as RepeaterDeleteAction
};
use app\modules\users\components\rbac\items\Permissions;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\controllers
 */
final class ConstantController extends BaseController
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
                            Permissions::CONSTANT_LIST,
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            Permissions::CONSTANT_VIEW_MAIN,
                            Permissions::CONSTANT_VIEW_GROUP,
                            Permissions::CONSTANT_VIEW_ALL,
                            Permissions::CONSTANT_VIEW_DELETE_MAIN,
                            Permissions::CONSTANT_VIEW_DELETE_GROUP,
                            Permissions::CONSTANT_VIEW_DELETE_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantRepository::get(
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
                        'actions' => ['create', 'createmass', 'addconstant', 'deleteconstant'],
                        'allow' => true,
                        'roles' => [Permissions::CONSTANT_CREATE],
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => [
                            Permissions::CONSTANT_EDIT_MAIN,
                            Permissions::CONSTANT_EDIT_GROUP,
                            Permissions::CONSTANT_EDIT_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = ConstantRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            Permissions::CONSTANT_DELETE_MAIN,
                            Permissions::CONSTANT_DELETE_GROUP,
                            Permissions::CONSTANT_DELETE_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = ConstantRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            Permissions::CONSTANT_ENABLE_MAIN,
                            Permissions::CONSTANT_ENABLE_GROUP,
                            Permissions::CONSTANT_EDIT_ALL
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantRepository::get(
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
        private readonly ConstantService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => ConstantSearch::class
            ],
            'create' => [
                'class' => CreateEditAction::class,
                'entity' => ReportConstantEntity::class,
                'entityScenario' => BaseAR::SCENARIO_INSERT,
                'model' => ConstantModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Reports.Constant',
                'successMessage' => 'Новая константа для отчета добавлена',
                'errorMessage' => 'При создании константы возникли ошибки. Пожалуйста, обратитесь к администратору',
                'redirectUrl' => Url::to(['/reports/constant'])
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => ConstantRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => ConstantModel::class,
                'exceptionMessage' => 'Запрашиваемая константа не найдена, или недоступна'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'entityScenario' => BaseAR::SCENARIO_UPDATE,
                'repository' => ConstantRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => ConstantModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Reports.Constant',
                'refresh' => true,
                'successMessage' => 'Константа успешно обновлена',
                'errorMessage' => 'При обработке константы возникли ошибки. Пожалуйста, обратитесь к администратору',
                'exceptionMessage' => 'Запрашиваемая константа не найдена, или недоступна',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => ConstantRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении константы возникли ошибки. Пожалуйста, обратитесь к администратору',
                'successMessage' => 'Константа скрыта. Использовать ее нельзя. При этом, во всех ранее переданных отчетах, она будет присутствовать',
                'exceptionMessage' => 'Запрашиваемая константа не найдена, или недоступна'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => ConstantRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Запрашиваемая константа не найдена, или недоступна'
            ],
            'addconstant' => [
                'class' => AddAction::class,
                'model' => ConstantModel::class,
                'constructClass' => ReportConstantEntity::class,
                'contentPath' => '@resources/views/reports/constant/_partial/form_generateItems',
            ],
            'deleteconstant' => [
                'class' => RepeaterDeleteAction::class,
            ],
        ];
    }

    public function actionCreatemass(): string|Response
    {
        $request = $this->request;
        $models = [];
        $errors = [];

        if ($request->isPost) {
            $post = $request->post();
            $formData = $post['ConstantModel'];

            foreach (array_keys($formData) as $index) {
                $models[$index] = new ConstantModel(new ReportConstantEntity([
                    'scenario' => BaseAR::SCENARIO_INSERT
                ]));
            }

            if (!empty($models) && (ConstantModel::loadMultiple($models, $post, 'ConstantModel'))) {
                if (ConstantModel::validateMultiple($models)) {
                    foreach ($models as $model) {
                        $this->service->save($model, 'Reports.Constant', 'При обработке константы возникли ошибки. Пожалуйста, обратитесь к администратору');
                    }

                    Yii::$app->getSession()->setFlash('success', 'Константы успешно добавлены');
                    return $this->redirect('/constant');
                }
            }
        }

        return $this->render('createMass', [
            'models' => empty($models) ? [new ConstantModel(new ReportConstantEntity([
                'scenario' => BaseAR::SCENARIO_INSERT
            ]))] : $models,
            'errors' => $errors,
        ]);
    }
}
