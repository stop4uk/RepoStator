<?php

namespace app\modules\admin\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

use app\helpers\{
    CommonHelper, HtmlPurifier
};
use app\modules\users\{
    components\rbac\items\Permissions,
    components\rbac\items\Roles,
    entities\UserEntity,
    repositories\UserRepository,
    helpers\RbacHelper,
    helpers\UserHelper
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\admin\search
 */
final class UserSearch extends Model
{
    public $email;
    public $name;
    public $phone;
    public $account_status;
    public $hasGroup;

    public readonly bool $onlyActive;
    public readonly array $groups;
    private readonly array $allowUsers;

    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->onlyActive = RbacHelper::getOnlyActiveRecordsState([
            Permissions::ADMIN_USER_VIEW_GROUP,
             Permissions::ADMIN_USER_VIEW_DELETE_GROUP
        ]);
        $this->groups = RbacHelper::getAllowGroupsArray('admin.user.list.all');
        $this->allowUsers = UserRepository::getAllow(
            groups: $this->groups,
            active: $this->onlyActive
        );
    }

    public function rules(): array
    {
        return [
            ['name', 'string', 'length' => [2,28]],
            ['phone', 'integer'],
            ['phone', 'string', 'max' => 10],
            ['account_status', 'integer'],
            ['account_status', 'in', 'range' => CommonHelper::getFilterReplaceData(UserEntity::STATUSES)],
            ['hasGroup', 'integer'],
            ['hasGroup', 'in', 'range' => array_keys($this->groups)],

            [['email', 'name'], 'filter', 'filter' => fn($value) => HtmlPurifier::process($value)]
        ];
    }

    public function attributeLabels(): array
    {
        return UserHelper::labels();
    }

    public function search($params): ActiveDataProvider
    {
        $query = match (Yii::$app->getUser()->can(Roles::ADMIN)) {
            true => UserRepository::getAll(active: $this->onlyActive),
            false => UserRepository::getAllBy(
                condition: ['id' => array_keys($this->allowUsers)],
                relations: ['group'],
                active: $this->onlyActive
            )
        };

        $query->andFilterWhere(['!=', 'id', Yii::$app->getUser()->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC]
            ],
            'pagination' => [
                'pageSize' => 15
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $this->cleanDataProvider($dataProvider);
        }

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere([
                'or',
                ['like', 'lastname', $this->name],
                ['like', 'firstname', $this->name],
                ['like', 'middlename', $this->name]
            ])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['=', 'account_status', CommonHelper::getFilterReplace($this->account_status)]);

        if ($this->hasGroup) {
            $dataProvider = $this->filterByGroup($dataProvider);
        }

        return $this->cleanDataProvider($dataProvider);
    }

    private function filterByGroup(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $models = $dataProvider->getModels();
        if (!$models) {
            return $dataProvider;
        }

        foreach ($models as $index => $model) {
            if (
                !isset($model->group->id)
                || $this->hasGroup != $model->group->group_id
                || !in_array($this->hasGroup, array_keys($this->groups))
            ) {
                unset($models[$index]);
            }
        }

        $dataProvider->setModels($models);
        return $dataProvider;
    }

    private function cleanDataProvider(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $models = $dataProvider->getModels();

        if ($models) {
            foreach ($models as $index => $model) {
                if (
                    !$model->record_status
                    && (
                        !Yii::$app->getUser()->can(Permissions::ADMIN_USER_VIEW_DELETE_GROUP, [
                            'id' => $model->id,
                            'record_status' => $model->record_status
                        ])
                        && !Yii::$app->getUser()->can(Permissions::ADMIN_USER_VIEW_DELETE_ALL, [
                            'id' => $model->id,
                            'record_status' => $model->record_status
                        ])
                    )
                ) {
                    unset($models[$index]);
                }
            }
        }

        $dataProvider->setModels($models);
        return $dataProvider;
    }
}