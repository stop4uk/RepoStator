<?php

namespace app\services\group;

use app\base\BaseService;
use app\entities\group\GroupEntity;
use app\interfaces\BaseARInterface;

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