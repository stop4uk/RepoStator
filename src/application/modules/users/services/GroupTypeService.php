<?php

namespace stop4uk\users\services;

use app\components\base\{
    BaseARInterface,
    BaseService
};
use stop4uk\users\entities\GroupEntity;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\services\group
 */
final class GroupTypeService extends BaseService
{
    public function afterDelete(BaseARInterface $entity): bool
    {
        GroupEntity::updateAll(['type_id' => null], ['type_id' => $entity->id]);
        return true;
    }
}