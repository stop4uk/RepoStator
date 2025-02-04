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
use app\helpers\CommonHelper;
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
        $isNewRecordWhenLoad = $model->getIsNewEntity();
        $session = Yii::$app->getSession();
        $sessionKey = implode('_', [Yii::$app->controller->getUniqueId(), Yii::$app->getUser()->id]);
        $sessionFiles = $session->get($sessionKey);

        $model->getEntity()->recordAction($model);
        $transaction = Yii::$app->db->beginTransaction();
        if ($saveEntity = CommonHelper::saveAttempt($model->getEntity(), 'Reports.Template')) {
            $saveTemplate = true;

            if ($isNewRecordWhenLoad && $sessionFiles) {
                foreach ($sessionFiles as $file) {
                    $saveFile = $model->getEntity()->attachFile(
                        inputFile: $file['fullPath'],
                        type: $file['file_type']
                            ?: array_key_first($model->getEntity()->attachRules),
                        name: $file['name'],
                        extension: $file['extension'],
                        unlinkFile: false
                    );

                    if (!$saveFile) {
                        $saveTemplate = false;
                        break;
                    }
                }
            }

            if($saveTemplate && $sessionFiles) {
                foreach ($sessionFiles as $sessionFile) {
                    try{unlink($sessionFile['fullPath']);} catch (ErrorException $e){}
                }
                $session->remove($sessionKey);
            }

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
}
