<?php

namespace app\useCases\admin\controllers;

use yii\filters\AccessControl;
use yii\helpers\Url;

use app\actions\{
    CreateEditAction,
    DeleteAction,
    EnableAction,
    IndexAction,
    ViewAction
};
use app\components\base\{
    BaseController,
    BaseAR
};
use app\useCases\users\{
    entities\user\UserEntity,
    models\user\UserModel,
    repositories\user\UserRepository,
    services\UserService
};

use app\useCases\admin\search\UserSearch;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\admin
 */
final class UsersController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => [
                            'admin.user.list',
                            'admin.user.list.all',
                        ],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => [
                            'admin.user.view.group',
                            'admin.user.view.all',
                            'admin.user.view.delete.group',
                            'admin.user.view.delete.all',
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = UserRepository::get(
                                id: $this->request->get('id'),
                                active: false
                            );

                            return [
                                'id' => $recordInformation->id,
                                'record_status'=> $recordInformation->record_status

                            ];
                        },
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => [
                            'admin.user.create',
                        ],
                    ],
                    [
                        'actions' => ['edit'],
                        'allow' => true,
                        'roles' => [
                            'admin.user.edit.group',
                            'admin.user.edit.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = UserRepository::get($this->request->get('id'));

                            return [
                                'id' => $recordInformation->id,
                                'record_status'=> $recordInformation->record_status

                            ];
                        },
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => [
                            'admin.user.delete.group',
                            'admin.user.delete.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = UserRepository::get($this->request->get('id'));

                            return [
                                'id' => $recordInformation->id,
                                'record_status'=> $recordInformation->record_status

                            ];
                        },
                    ],
                    [
                        'actions' => ['enable'],
                        'allow' => true,
                        'roles' => [
                            'admin.user.enable.group',
                            'admin.user.enable.all'
                        ],
                        'roleParams' => function($rule) {
                            $recordInformation = UserRepository::get(
                                id: $this->request->get('id'),
                                active: false
                            );

                            return [
                                'id' => $recordInformation->id,
                                'record_status'=> $recordInformation->record_status

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
        private readonly UserService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => UserSearch::class
            ],
            'create' => [
                'class' => CreateEditAction::class,
                'entity' => UserEntity::class,
                'entityScenario' => BaseAR::SCENARIO_INSERT,
                'model' => UserModel::class,
                'service' => $this->service,
                'successMessage' => 'Новый пользователь добавлен',
                'redirectUrl' => Url::to(['/admin/users'])
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => UserRepository::class,
                'repositoryRelations' => ['group', 'rights'],
                'requestID' => $this->request->get('id'),
                'model' => UserModel::class,
                'exceptionMessage' => 'Пользователь не найден, или, просмотр запрещен'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'entityScenario' => UserEntity::SCENARIO_UPDATE_BY_ADMIN,
                'repository' => UserRepository::class,
                'repositoryRelations' => ['group', 'rights'],
                'requestID' => $this->request->get('id'),
                'model' => UserModel::class,
                'service' => $this->service,
                'refresh' => true,
                'successMessage' => 'Пользоватуль успешно обновлен',
                'exceptionMessage' => 'Пользователь не найден, или, просмотр запрещен',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => UserRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении пользователя возникли ошибки. Пожалуйста, проверьте логи',
                'successMessage' => 'Пользователь скрыт. При этом, все переданные сведения и внесенные им изменения сохраняются',
                'exceptionMessage' => 'Пользователь не найден, или, просмотр запрещен'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => UserRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Пользователь не найден, или, просмотр запрещен'
            ],
        ];
    }
}
