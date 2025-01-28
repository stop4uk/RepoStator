<?php

namespace app\modules\users\services;

use app\components\base\{
    BaseARInterface,
    BaseService
};
use app\modules\users\entities\GroupEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\modules\users\services
 */
final class GroupTypeService extends BaseService
{
    public function afterDelete(BaseARInterface $entity): bool
    {
        GroupEntity::updateAll(['type_id' => null], ['type_id' => $entity->id]);
        return true;
    }
}