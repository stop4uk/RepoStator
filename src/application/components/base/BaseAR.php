<?php

namespace app\components\base;

use yii\db\ActiveRecord;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\base
 */
class BaseAR extends ActiveRecord implements BaseARInterface
{
    const RSTATUS_DELETED = 0;
    const RSTATUS_ACTIVE = 1;

    const SCENARIO_INSERT = 'insert';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_DELETE = 'delete';
    const SCENARIO_CHANGE_RECORD_STATUS = 'changeRecordStatus';

    public const RSTATUSES = [
        self::RSTATUS_DELETED,
        self::RSTATUS_ACTIVE
    ];
}
