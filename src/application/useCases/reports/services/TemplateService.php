<?php

namespace app\useCases\reports\services;

use Yii;
use yii\base\Exception;

use app\components\{
    base\BaseARInterface,
    base\BaseService,
};
use app\helpers\CommonHelper;
use app\useCases\reports\models\TemplateModel;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\services\report
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
        $isNewRecord = $model->getIsNewEntity();
        $loadTemplateData = Yii::$app->getCache()->get('reportTempUpload_' . Yii::$app->getUser()->getId());

        $model = $this->beforeSetAttributes($model);
        $model->getEntity()->recordAction($model);

        $transaction = Yii::$app->db->beginTransaction();
        if (
            $saveEntity = CommonHelper::saveAttempt(
                entity: $model->getEntity(),
                category: 'Reports.Template'
            )
        ) {
            $saveTempalte = true;
            if ($isNewRecord && $loadTemplateData) {
                if (!$model->getEntity()->attachFile($loadTemplateData)) {
                    $saveTempalte = false;
                }
            }

            if($saveTempalte) {
                Yii::$app->getCache()->delete('reportTempUpload_' . Yii::$app->getUser()->getId());
                $transaction->commit();
                return $saveEntity;
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
