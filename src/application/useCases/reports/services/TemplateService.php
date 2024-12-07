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
        $cache = Yii::$app->getCache();
        $cacheKey = env('YII_UPLOADS_PATH_TEMPPATH') . Yii::$app->getUser()->getId();
        $cacheFiles = $cache->get($cacheKey);

        $model->getEntity()->recordAction($model);
        $transaction = Yii::$app->db->beginTransaction();
        if ($saveEntity = CommonHelper::saveAttempt($model->getEntity(), 'Reports.Template')) {
            $saveTemplate = true;

            if ($isNewRecord && $cacheFiles) {
                foreach ($cacheFiles as $file) {
                    if (!$model->getEntity()->attachFile(
                        inputFile: $file['fullPath'],
                        type: array_key_first($model->getEntity()->attachRules),
                        name: $file['name'],
                        extension: $file['extension']
                    )) {
                        $saveTemplate = false;
                        break;
                    }
                }
            }

            if($saveTemplate) {
                $cache->delete($cacheKey);
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
