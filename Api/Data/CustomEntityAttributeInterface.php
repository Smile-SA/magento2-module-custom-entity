<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api\Data;

use Smile\ScopedEav\Api\Data\AttributeInterface;

/**
 * Custom entity attribute interface.
 *
 * @api
 */
interface CustomEntityAttributeInterface extends AttributeInterface
{
    /**
     * Entity code. Can be used as part of method name for entity processing.
     * @var string
     */
    const ENTITY_TYPE_CODE = 'smile_custom_entity';
}
