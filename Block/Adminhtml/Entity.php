<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Adminhtml;

use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity listing main container.
 */
class Entity extends \Smile\ScopedEav\Block\Adminhtml\AbstractEntity
{
    /**
     * {@inheritDoc}
     */
    protected function getEntityTypeCode()
    {
        return CustomEntityInterface::ENTITY;
    }
}
