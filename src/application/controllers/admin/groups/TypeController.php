<?php

namespace app\controllers\admin\groups;

use app\actions\{CreateEditAction, DeleteAction, EnableAction, IndexAction, ViewAction,};
use app\components\base\BaseController;
use entities\group\GroupTypeEntity;
use group\GroupTypeService;
use models\group\GroupTypeModel;
use repositories\group\GroupTypeRepository;
use search\group\GroupTypeSearch;
use yii\filters\AccessControl;
use yii\helpers\Url;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\controllers\admin\groups
 */
final class TypeController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin.groupType'],
                    ],
                ],
            ],
        ];
    }

    public function __construct(
        $id,
        $module,
        private readonly GroupTypeService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => GroupTypeSearch::class
            ],
            'create' => [
                'class' => CreateEditAction::class,
                'entity' => GroupTypeEntity::class,
                'model' => GroupTypeModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Groups.Type',
                'successMessage' => 'Новый тип группы добавлен',
                'errorMessage' => 'При добавлении типа группы возникли ошибки. Пожалуйста, обратитесь к администратору',
                'redirectUrl' => Url::to(['/admin/groups/type'])
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => GroupTypeRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => GroupTypeModel::class,
                'exceptionMessage' => 'Запрашиваемый тип группы не найден'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'repository' => GroupTypeRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => GroupTypeModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Groups.Type',
                'refresh' => true,
                'successMessage' => 'Тип группы успешно обновлен',
                'errorMessage' => 'При обновлении типа группы возникли ошибки. Пожалуйста, обратитесь к администратору',
                'exceptionMessage' => 'Запрашиваемый тип группы не найден',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => GroupTypeRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении типа группы возникли ошибки. Пожалуйста, проверьте логи',
                'successMessage' => 'Тип группы скрыт. Справочник групп обновлен',
                'exceptionMessage' => 'Запрашиваемый тип группы не найден'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => GroupTypeRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Запрашиваемый тип группы не найден'
            ],
        ];
    }
}
