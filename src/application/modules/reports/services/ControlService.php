<?php

namespace app\modules\reports\services;

use Yii;
use yii\base\Exception;
use yii\helpers\Json;

use app\components\{
    base\BaseService,
    base\BaseModelInterface,
    base\BaseARInterface,

};
use app\modules\reports\entities\ReportDataChangeEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\reports\services
 */
final class ControlService extends BaseService
{
    public function edit(BaseModelInterface $model): BaseARInterface
    {
        $changeData = $this->beforeEdit($model);
        $model->getEntity()->recordAction($model);

        $transaction = Yii::$app->db->beginTransaction();

        if ($model->getEntity()->save(logCategory: 'Reports.Control')) {
            if ($this->afterEdit($model, $changeData)) {
                $transaction->commit();
                return $model->getEntity();
            }
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', 'При редактировании переданных сведений возникла ошибка. Пожалуйста, обратитесь к администратору'));
    }

    public function delete(
        $entity,
        $errorMessage = null
    ): bool {
        $transaction = Yii::$app->db->beginTransaction();

        $entity->updated_at = time();
        $entity->updated_uid = Yii::$app->getUser()->getId();
        if ($entity->softDelete()) {
            $transaction->commit();
            return true;
        }

        $transaction->rollBack();
        throw new Exception(Yii::t('exceptions', $errorMessage));
    }

    private function beforeEdit(BaseModelInterface $model): array
    {
        $oldContent = Json::decode($model->getEntity()->content);
        $newContent = $model->content;
        $changes = [];

        foreach ($oldContent as $constant => $value) {
            if (
                !in_array($constant, array_keys($newContent))
                || !$newContent[$constant]
            ) {
                $changes[$constant] = [
                    'operation' => ReportDataChangeEntity::OPERATION_DELETE,
                    'value' => $value
                ];

                continue;
            }

            if ($value != $newContent[$constant]) {
                $changes[$constant] = [
                    'operation' => ReportDataChangeEntity::OPERATION_EDIT,
                    'value' => $newContent[$constant],
                    'oldValue' => $value
                ];
            }
        }

        foreach ($newContent as $constant => $value) {
            if (
                !in_array($constant, array_keys($oldContent))
                && $value
            ) {
                $changes[$constant] = [
                    'operation' => ReportDataChangeEntity::OPERATION_ADD,
                    'value' => $value
                ];
            }
        }

        return $changes;
    }

    private function afterEdit(
        BaseModelInterface $model,
        array              $changeData = []
    ): bool {
        if (!$changeData) {
            return true;
        }

        $changes = new ReportDataChangeEntity();
        $changes->report_id = $model->report->id;
        $changes->data_id = $model->getEntity()->id;
        $changes->content = $changeData;

        if (!$changes->validate()) {
            Yii::error('Error validate ReportDataChangeEntity: ' . Json::encode($changes->errors), 'Reports.Control');
            return true;
        }

        return $changes->save();
    }
}
