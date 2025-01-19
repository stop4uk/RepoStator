<?php

namespace stop4uk\users\base;

use yii\base\Model;

/**
 * @author Stop4uk <stop4uk@yandex.ru>
 * @package app\base
 */
class BaseModel extends Model implements BaseModelInterface
{
    public function __construct(
        private readonly BaseARInterface $entity,
        array $config = []
    ) {
        if (!$this->isNewEntity) {
            $this->setAttributes($this->entity->toArray());
        }

        parent::__construct($config);
    }

    public function getEntity(): BaseARInterface
    {
        return $this->entity;
    }

    public function getIsNewEntity(): bool
    {
        return $this->entity->id === null;
    }

    protected function getUniqueFilterString(bool $considerID = false): string
    {
        $string = 'record_status = ' . BaseAR::RSTATUS_ACTIVE;

        if ( $considerID && !$this->getIsNewEntity() ) {
            $string .= ' AND id != ' . $this->entity->id;
        }

        return $string;
    }
}
