<?php

declare(strict_types=1);

namespace Smile\CustomEntity\ViewModel\Attribute;

use Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend;
use Smile\CustomEntity\Model\Source\Attribute\CustomEntity as CustomEntitySource;

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
            'smile_custom_entity_select' => CustomEntitySource::class,
            default => parent::getAttributeSourceModelByInputType($inputType),
        };
    }

    /**
     * @inheritDoc
     */
    public function getAttributeBackendModelByInputType(string $inputType): ?string
    {
        return match ($inputType) {
            'smile_custom_entity_select' => ArrayBackend::class,
            default => parent::getAttributeBackendModelByInputType($inputType),
        };
    }

    /**
     * Detect backend storage type using frontend input type.
     */
    public function getAttributeBackendTypeByInput(string $inputType): ?string
    {
        return match ($inputType) {
            'smile_custom_entity_select' => 'varchar',
            default => parent::getAttributeBackendTypeByInput($inputType),
        };
    }
}
