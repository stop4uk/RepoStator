<?php

namespace stop4uk\users\services;

use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;

use app\components\base\{
    BaseModelInterface,
    BaseARInterface,
    BaseService,
};
use app\helpers\CommonHelper;
use stop4uk\users\entities\GroupNestedEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\services\group
 */
final class GroupService extends BaseService
{
    public function save(
        BaseModelInterface $model,
        string             $categoryForLog,
        string             $errorMessage
    ): BaseARInterface {
        $newEntity = $model->isNewEntity;
        $model->getEntity()->recordAction($model);

        $transaction = Yii::$app->db->beginTransaction();
        if (
            $saveModel = CommonHelper::saveAttempt(
                entity: $model->getEntity(),
                category: $categoryForLog
            )
        ) {
            if ( $this->afterSave($model, $newEntity) ) {
                $transaction->commit();
                return $saveModel;
            }
        }

        $transaction->rollBack();
        throw new Exception($errorMessage);
    }

    protected function afterDelete(BaseARInterface $entity): bool
    {
        $parent = GroupNestedEntity::find()->where(['id' => 1])->limit(1)->one();
        $nested = GroupNestedEntity::find()->where(['group_id' => $entity->id])->limit(1)->one();
        if ( $nested) {
            $childrensArray = ArrayHelper::map($nested->children(1)->all(), 'id', 'id');
            if ( $childrensArray ) {
                foreach (GroupNestedEntity::findAll(['id' => $childrensArray]) as $children) {
                    $children->appendTo($parent)->save(false);
                }
            }

            $nested->deleteWithChildren();
        }

        return true;
    }

    private function afterSave(
        BaseModelInterface $model,
        bool               $newEntity
    ): bool {
        if ( !$newEntity ) {
            return true;
        }

        $parent = GroupNestedEntity::find()->where(['id' => 1])->limit(1)->one();
        $nested = new GroupNestedEntity();
        $nested->group_id = $model->getEntity()->id;
        $nested->appendTo($parent);

        return (bool)CommonHelper::saveAttempt(
            entity: $nested,
            category: 'Groups'
        );
    }
}
