<?php

declare(strict_types=1);

namespace Smile\CustomEntity\ViewModel\Attribute;

use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Smile\CustomEntity\Model\Source\Attribute\CustomEntity as CustomEntitySource;
use Smile\ScopedEav\Api\Data\AttributeInterface;

/**
 * CustomEntity EAV view model.
 */
class Data extends \Smile\ScopedEav\ViewModel\Data
{
    /**
     * @inheritDoc
     */
    public function getAttributeSourceModelByInputType(string $inputType): ?string
    {
        return match ($inputType) {
            'smile_custom_entity_select', 'smile_custom_entity_multiselect' => CustomEntitySource::class,
            default => parent::getAttributeSourceModelByInputType($inputType),
        };
    }

    /**
     * @inheritDoc
     */
    public function getAttributeBackendModelByInputType(string $inputType): ?string
    {
        return match ($inputType) {
            'smile_custom_entity_multiselect' => ArrayBackend::class,
            default => parent::getAttributeBackendModelByInputType($inputType),
        };
    }

    /**
     * Detect backend storage type using frontend input type.
     */
    public function getBackendTypeByInput(AttributeInterface $attribute, string $frontendInput): ?string
    {
        return match ($frontendInput) {
            'image', 'smile_custom_entity_multiselect' => 'varchar',
            'smile_custom_entity_select' => 'int',
            default => $attribute->getBackendTypeByInput($frontendInput),
        };
    }
}
