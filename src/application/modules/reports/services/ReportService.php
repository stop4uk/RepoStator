<?php

namespace app\modules\reports\services;

use Yii;
use yii\base\Exception;

use app\components\{
    base\BaseService,
    base\BaseAR,
    base\BaseARInterface
};
use app\modules\reports\entities\{
    ReportDataEntity,
    ReportFormTemplateEntity,
    ReportStructureEntity
};

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\services\report
 */
final class ReportService extends BaseService
{
    protected function afterDelete($entity): bool
    {
        return $this->softDeleteDirectData($entity);
    }

    private function softDeleteDirectData(BaseARInterface $entity): bool
    {
        $updatedClass = [
            new ReportStructureEntity(),
            new ReportFormTemplateEntity(),
            new ReportDataEntity(),
        ];

        $updatedAttributes = [
            'updated_at' => time(),
            'updated_uid' => Yii::$app->getUser()->getId(),
            'record_status' => BaseAR::RSTATUS_DELETED
        ];

        $filters = [
            'report_id' => $entity->id
        ];

        foreach ($updatedClass as $class) {
            try {
                Yii::$container->invoke([$class, 'updateAll'], [$updatedAttributes, $filters]);
            } catch (Exception $e) {
                Yii::error($e->getMessage(), 'Reports.List');
                return false;
            }
        }

        return true;
    }
}
