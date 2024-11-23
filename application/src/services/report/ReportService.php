<?php

namespace app\services\report;

use Yii;
use yii\base\Exception;

use app\base\{
    BaseAR,
    BaseService
};
use app\interfaces\BaseARInterface;
use app\entities\{
    report\ReportDataEntity,
    report\ReportFormTemplateEntity,
    report\ReportStructureEntity,
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
