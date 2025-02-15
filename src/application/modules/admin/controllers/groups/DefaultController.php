<?php

namespace app\modules\admin\controllers\groups;

use yii\filters\AccessControl;
use yii\helpers\{
    ArrayHelper,
    Url
};
use klisl\nestable\NodeMoveAction;

use app\components\base\BaseController;
use app\actions\{
    IndexAction,
    ViewAction,
    CreateEditAction,
    DeleteAction,
    EnableAction,
};
use app\modules\admin\search\GroupSearch;
use app\modules\users\{
    components\rbac\items\Permissions,
    entities\GroupEntity,
    entities\GroupNestedEntity,
    models\GroupModel,
    repositories\GroupRepository,
    services\GroupService
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\admin\controllers\groups
 */
final class DefaultController extends BaseController
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [
                            Permissions::ADMIN_GROUP
                        ],
                    ],
                ],
            ],
        ];
    }

    public function __construct(
        $id,
        $module,
        private readonly GroupService $service,
        $config = []
    ) {
        parent::__construct($id, $module, $config);
    }

    public function actions(): array
    {
        return [
            'nodeMove' => [
                'class' => NodeMoveAction::class,
                'modelName' => GroupNestedEntity::class,
            ],
            'index' => [
                'class' => IndexAction::class,
                'searchModel' => GroupSearch::class
            ],
            'create' => [
                'class' => CreateEditAction::class,
                'entity' => GroupEntity::class,
                'model' => GroupModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Groups',
                'successMessage' => 'Новая группа добавлена',
                'errorMessage' => 'При добавлении группы возникли ошибки. Пожалуйста, обратитесь к администратору',
                'redirectUrl' => Url::to(['/admin/groups'])
            ],
            'view' => [
                'class' => ViewAction::class,
                'repository' => GroupRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => GroupModel::class,
                'exceptionMessage' => 'Запрашиваемая группа не найдена'
            ],
            'edit' => [
                'class' => CreateEditAction::class,
                'actionType' => 'edit',
                'repository' => GroupRepository::class,
                'requestID' => $this->request->get('id'),
                'model' => GroupModel::class,
                'service' => $this->service,
                'categoryForLog' => 'Groups',
                'refresh' => true,
                'successMessage' => 'Группа успешно обновлена',
                'errorMessage' => 'При обновлении группы возникли ошибки. Пожалуйста, обратитесь к администратору',
                'exceptionMessage' => 'Заправшиваемая группа не найдена',
            ],
            'delete' => [
                'class' => DeleteAction::class,
                'repository' => GroupRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'errorMessage' => 'При удалении группы возникли ошибки. Пожалуйста, проверьте логи',
                'successMessage' => 'Группа скрыта. Из всех списков пропадет автоматичеки',
                'exceptionMessage' => 'Заправшиваемая группа не найдена'
            ],
            'enable' => [
                'class' => EnableAction::class,
                'repository' => GroupRepository::class,
                'requestID' => $this->request->get('id'),
                'service' => $this->service,
                'exceptionMessage' => 'Заправшиваемая группа не найдена'
            ],
        ];
    }

    public function actionMap(): string
    {
        $groups = ArrayHelper::map(GroupEntity::find()->all(), 'id', 'name');
        $query = GroupNestedEntity::find()
            ->with(['group'])
            ->where(['depth' => 0]);

        return $this->render('map', compact('query', 'groups'));
    }
}
