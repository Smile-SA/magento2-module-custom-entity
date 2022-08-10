<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Set;

use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;

/**
 * Custom entity attribute set admin list controller.
 */
class Save extends \Smile\ScopedEav\Controller\Adminhtml\Set\Save
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'Smile_CustomEntity::attributes_set';

    /**
     * @var string
     */
    protected $entityTypeCode = CustomEntityAttributeInterface::ENTITY_TYPE_CODE;
}
