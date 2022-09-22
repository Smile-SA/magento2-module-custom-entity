<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Adminhtml;

use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\ScopedEav\Block\Adminhtml\AbstractEntity;

/**
 * Custom entity listing main container.
 */
class Entity extends AbstractEntity
{
    /**
     * @inheritDoc
     */
    protected function getEntityTypeCode(): string
    {
        return CustomEntityInterface::ENTITY;
    }
}
