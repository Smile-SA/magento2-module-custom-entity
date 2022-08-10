<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api\Data;

/**
 * Custom entity attribute interface.
 *
 * @api
 */
interface CustomEntityAttributeInterface extends \Smile\ScopedEav\Api\Data\AttributeInterface
{
    /**
     * Entity code. Can be used as part of method name for entity processing.
     */
    const ENTITY_TYPE_CODE = 'smile_custom_entity';
}
