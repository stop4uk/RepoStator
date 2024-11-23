<?php

namespace app\base;

use Yii;
use yii\base\{
    Component,
    Exception
};

use app\interfaces\{
    BaseARInterface,
    ModelInterface,
    ServiceInterface
};
use app\helpers\CommonHelper;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\base
 */
class BaseService extends Component implements ServiceInterface
{
    public function save(
        ModelInterface $model,
        string $categoryForLog,
        string $errorMessage
    ): BaseARInterface|bool {
        $entity = $model->getEntity();
        $entity->recordAction($model);

        $transaction = $entity::getDb()->beginTransaction();
        if ( $saveModel = CommonHelper::saveAttempt($entity, $categoryForLog) ) {
            $transaction->commit();
            return $saveModel;
        }

        $transaction->rollBack();
        throw new Exception($errorMessage);
    }

    public function delete(
        BaseARInterface $entity,
        string $errorMessage
    ): bool {
        $entity->updated_at = time();
        $entity->updated_uid = Yii::$app->getUser()->getId();

        $transaction = $entity::getDb()->beginTransaction();
        if ( $entity->softDelete() && $this->afterDelete($entity) ) {
            $transaction->commit();
            return true;
        }

        $transaction->rollBack();
        throw new Exception($errorMessage);
    }

    public function enable(BaseARInterface $entity): bool
    {
        $entity->record_status = BaseAR::RSTATUS_ACTIVE;

        $transaction = $entity::getDb()->beginTransaction();
        if ( CommonHelper::saveAttempt($entity, 'Application') ) {
            $transaction->commit();
            return true;
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', 'Произошла ошибка при восстановлении статуса записи. Пожалуйста, проверьте логи'));
    }

    protected function afterDelete(BaseARInterface $entity): bool
    {
        return true;
    }
}
