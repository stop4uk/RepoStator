<?php

namespace app\controllers\reports;

use app\actions\{CreateEditAction, DeleteAction, EnableAction, IndexAction, ViewAction,};
use app\components\base\{BaseController};
use app\components\base\BaseAR;
use app\entities\report\ReportConstantEntity;
use app\models\report\ConstantModel;
use app\repositories\report\ConstantBaseRepository;
use app\search\report\ConstantSearch;
use app\services\report\ConstantService;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\reports
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
                            'constant.list',
                        ],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['view'],
                        'roles' => [
                            'constant.view.main',
                            'constant.view.group',
                            'constant.view.all',
                            'constant.view.delete.main',
                            'constant.view.delete.group',
                            'constant.view.delete.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantBaseRepository::get(
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
                        'roles' => ['constant.create'],
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => [
                            'constant.edit.main',
                            'constant.edit.group',
                            'constant.edit.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantBaseRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = ConstantBaseRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            'constant.delete.main',
                            'constant.delete.group',
                            'constant.delete.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantBaseRepository::get($this->request->get('id'));

                            return [
                                'created_uid' => $recordInformation?->created_uid,
                                'created_gid' => $recordInformation?->created_gid,
                                'record_status' => $recordInformation?->record_status
                            ];
                        },
                        'matchCallback' => function($rule, $action) {
                            $recordInformation = ConstantBaseRepository::get($this->request->get('id'));
                            return ($recordInformation && $recordInformation->record_status);
                        }
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            'constant.enable.main',
                            'constant.enable.group',
                            'constant.enable.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = ConstantBaseRepository::get(
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
                'repository' => ConstantBaseRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => ConstantModel::class,
                'exceptionMessage' => 'Запрашиваемая константа не найдена, или недоступна'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'entityScenario' => BaseAR::SCENARIO_UPDATE,
                'repository' => ConstantBaseRepository::class,
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
                'repository' => ConstantBaseRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении константы возникли ошибки. Пожалуйста, обратитесь к администратору',
                'successMessage' => 'Константа скрыта. Использовать ее нельзя. При этом, во всех ранее переданных отчетах, она будет присутствовать',
                'exceptionMessage' => 'Запрашиваемая константа не найдена, или недоступна'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => ConstantBaseRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Запрашиваемая константа не найдена, или недоступна'
            ],
        ];
    }
}
