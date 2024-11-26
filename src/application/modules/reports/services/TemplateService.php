<?php

namespace services;

use app\components\base\{BaseModelInterface};
use app\components\base\BaseARInterface;
use app\components\base\BaseService;
use app\helpers\CommonHelper;
use Yii;
use yii\base\Exception;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\services\report
 */
final class TemplateService extends BaseService
{
    public function save($model, $categoryForLog = null, $errorMessage = null): BaseARInterface
    {
        $model = $this->beforeSetAttributes($model);
        $model->getEntity()->recordAction($model);

        $transaction = Yii::$app->db->beginTransaction();

        if (
            $saveEntity = CommonHelper::saveAttempt(
                entity: $model->getEntity(),
                category: 'Reports.Template'
            )
        ) {
            $transaction->commit();
            return $saveEntity;
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

    private function beforeSetAttributes(BaseModelInterface $model): BaseModelInterface
    {
        if (
            $model->oldTemplate
            && $model->uploadedFile
        ) {
            if ( !CommonHelper::deleteFileAttempt($model->oldTemplate) ) {
                return false;
            }
        }

        if (
            $model->uploadedFile &&
            $saveFile = CommonHelper::saveFileAttempt($model, 'uploadedFile', '@templates')
        ) {
            $model->table_template = $saveFile;
        }

        return $model;
    }
}
