<?php

namespace group;

use app\components\base\BaseARInterface;
use app\components\base\BaseService;
use entities\group\GroupEntity;

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