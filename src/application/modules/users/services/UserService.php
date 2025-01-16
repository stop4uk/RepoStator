<?php

namespace app\useCases\users\services;

use app\components\{base\BaseAR, base\BaseARInterface, base\BaseModelInterface, base\BaseService};
use app\helpers\CommonHelper;
use app\useCases\users\{entities\user\UserGroupEntity,
    entities\user\UserRightEntity,
    models\user\UserModel,
    repositories\user\UserGroupRepository};
use UserEvent;
use Yii;
use yii\base\Exception;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\services
 */
final class UserService extends BaseService
{
    const EVENT_AFTER_ADD = 'user.afterAdd';
    const EVENT_AFTER_CHANGE = 'user.afterChange';
    const EVENT_AFTER_DELETE = 'user.afterDelete';

    public function save(
        BaseModelInterface $model,
        string             $categoryForLog = null,
        string             $errorMessage = null
    ): BaseARInterface|bool {
        $isNewEntity = $model->isNewEntity;
        $oldAttribtues = $model->getEntity()->oldAttributes;
        $model->getEntity()->recordAction($model);

        if ($isNewEntity) {
            $model->getEntity()->account_cpass_required = 1;
        }

        $transaction = $model->getEntity()::getDb()->beginTransaction();
        if ( CommonHelper::saveAttempt($model->getEntity(), 'Users.Profile') ) {
            if ($this->afterSave($model)) {
                $transaction->commit();

                $oldAttribtues['sName'] = $model->getEntity()->shortName;
                $oldAttribtues['account_key'] = $model->getEntity()->account_key;

                if ( Yii::$app->settings->get('auth', 'users_notification_add') && $isNewEntity ) {
                    $this->trigger(self::EVENT_AFTER_ADD, new UserEvent([
                        'user' => $model->attributes,
                        'userEntity' => $oldAttribtues
                    ]));
                }

                if ( Yii::$app->settings->get('auth', 'users_notification_change') && !$isNewEntity ) {
                    $this->trigger(self::EVENT_AFTER_CHANGE, new UserEvent([
                        'user' => $model->attributes,
                        'userEntity' => $oldAttribtues
                    ]));
                }

                return true;
            }
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', 'При работе с пользователем возникла ошибка. Пожалуйста, проверьте логи'));
    }

    public function delete(
        $entity,
        $errorMessage = null
    ): bool {
        $transaction = $entity::getDb()->beginTransaction();

        $entity->account_status = $entity::STATUS_BLOCKED;
        $entity->updated_at = time();
        $entity->updated_uid = Yii::$app->getUser()->id;
        if ( $this->beforeDelete($entity) && $entity->softDelete() ) {
            $transaction->commit();

            if ( Yii::$app->settings->get('auth', 'users_notification_delete') ) {
                $this->trigger(self::EVENT_AFTER_DELETE, new UserEvent([
                    'userEntity' => $entity
                ]));
            }

            return true;
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', 'При удалении пользователя возникли ошибки. Пожалуйста, проверьте логи'));
    }

    public function enable(BaseARInterface $entity): bool
    {
        $entity->record_status = BaseAR::RSTATUS_ACTIVE;
        $entity->account_status = $entity::STATUS_ACTIVE;

        $transaction = $entity::getDb()->beginTransaction();
        if ( CommonHelper::saveAttempt($entity, 'Users') ) {
            $transaction->commit();
            return true;
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', 'Произошла ошибка при восстановлении статуса записи. Пожалуйста, проверьте логи'));
    }

    private function beforeDelete($entity): bool
    {
        $relationGroup = UserGroupRepository::getBy(['user_id' => $entity->id]);

        if ( $relationGroup ) {
            $relationGroup->updated_at = time();
            $relationGroup->updated_uid = Yii::$app->getUser()->id;

            if ( $relationGroup->softDelete() ) {
                UserRightEntity::deleteAll(['user_id' => $entity->id]);
                return true;
            }
        }

        return false;
    }

    private function afterSave(UserModel $model): bool
    {
        $state = true;

        if ( $model->group ) {
            if ( $model->hasGroup && $model->hasGroup != $model->group ) {
                $model->getEntity()->group->updated_at = time();
                $model->getEntity()->group->updated_uid = Yii::$app->getUser()->id;
                $model->getEntity()->group->softDelete();
            }

            if ( $model->group != $model->hasGroup ) {
                $relationGroup = new UserGroupEntity();
                $relationGroup->scenario = UserGroupEntity::SCENARIO_INSERT;
                $relationGroup->user_id = $model->getEntity()->id;
                $relationGroup->group_id = $model->group;

                if ( !$relationGroup->save() ) {
                    $state = false;
                    Yii::error($relationGroup->errors, 'Users.Groups');
                }
            }
        } else {
            if ( $model->hasGroup ) {
                $model->getEntity()->group->updated_at = time();
                $model->getEntity()->group->updated_uid = Yii::$app->getUser()->id;
                $model->getEntity()->group->softDelete();
            }
        }

        if ( $state && $model->rights ) {
            UserRightEntity::deleteAll(['user_id' => $model->getEntity()->id]);

            foreach($model->rights as $right) {
                $newListOfRights[] = [
                    'item_name' => $right,
                    'user_id' => $model->getEntity()->id,
                    'created_at' => time(),
                    'created_uid' => Yii::$app->getUser()->id
                ];
            }

            $batchInsert = Yii::$app->db->createCommand()
                ->batchInsert(UserRightEntity::tableName(), array_keys($newListOfRights[0]), $newListOfRights)
                ->execute();

            if ( !$batchInsert ) {
                $state = false;
            }
        }

        return $state;
    }
}
