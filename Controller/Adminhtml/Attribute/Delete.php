<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

/**
 * Custom entity attribute delete controller.
 */
class Delete extends \Smile\ScopedEav\Controller\Adminhtml\Attribute\Delete
{
    public const ADMIN_RESOURCE = 'Smile_CustomEntity::attributes_attributes';
}
