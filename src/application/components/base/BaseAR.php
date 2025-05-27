<?php

namespace app\components\base;

use Yii;
use yii\base\Exception;
use yii\db\ActiveRecord;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\componetns\base
 */
class BaseAR extends ActiveRecord implements BaseARInterface
{
    const RSTATUS_DELETED = 0;
    const RSTATUS_ACTIVE = 1;
    public const RSTATUSES = [
        self::RSTATUS_DELETED,
        self::RSTATUS_ACTIVE
    ];

    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';
    const SCENARIO_CHANGE_RECORD_STATUS = 'changeRecordStatus';

    public function save(
        $runValidation = true,
        $attributeNames = null,
        string|null $logCategory = null
    ): bool {
        if (!$this->validate($attributeNames)) {
            Yii::error($this->getErrors(), $logCategory);
            return false;
        }

        try {
            $save = parent::save($runValidation, $attributeNames);

            if ($save) {
                return true;
            }
        } catch (Exception $exception) {
            Yii::error($exception->getMessage(), $logCategory);
            return false;
        }

        return false;
    }
}
