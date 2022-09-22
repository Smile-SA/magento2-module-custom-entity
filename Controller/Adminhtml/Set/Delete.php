<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Set;

use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;

/**
 * Custom entity attribute set deletion controller.
 */
class Delete extends \Smile\ScopedEav\Controller\Adminhtml\Set\Delete
{
    public const ADMIN_RESOURCE = 'Smile_CustomEntity::attributes_set';

    protected string $entityTypeCode = CustomEntityAttributeInterface::ENTITY_TYPE_CODE;
}
