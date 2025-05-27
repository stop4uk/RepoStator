<?php

namespace app\modules\reports\services;

use Yii;
use yii\base\{
    ErrorException,
    Exception
};

use app\components\{
    base\BaseARInterface,
    base\BaseService,
};
use app\modules\reports\models\TemplateModel;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\services
 */
final class TemplateService extends BaseService
{
    /**
     * @param $model TemplateModel
     * @param $categoryForLog
     * @param $errorMessage
     * @return BaseARInterface
     * @throws Exception
     * @throws \yii\db\Exception
     */
    public function save($model, $categoryForLog = null, $errorMessage = null): BaseARInterface
    {
        $model->getEntity()->recordAction($model);
        $transaction = Yii::$app->db->beginTransaction();
        if ($model->getEntity()->save(logCategory: 'Reports.Template')) {
            try {
                $model->getEntity()->attachFileFromSession();
                $transaction->commit();

                return $model->getEntity();
            } catch(ErrorException $e) {
                Yii::error($e->getMessage(), 'Reports.Template');
            }
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', 'При работе с шаблоном отчета возникли ошибки. Пожалуйста, обратитесь к администратору'));
    }

    public function delete($entity, $categoryForLog = null, $errorMessage = null): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $entity->updated_at = time();
            $entity->updated_uid = Yii::$app->getUser()->getId();

            $entity->softDelete();
            $transaction->commit();

            return true;
        } catch (Exception $e) {
            Yii::error($e->getMessage(), 'Reports.Template');
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', 'При удалении шаблона отчета возникли ошибки. Пожалуйста, обратитесь к администратору'));
    }
}
