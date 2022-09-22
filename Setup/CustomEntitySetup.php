<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Setup;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Setup\EavSetup;
use Smile\CustomEntity\Model\CustomEntity as CustomEntityModel;
use Smile\CustomEntity\Model\CustomEntity\Attribute;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\Collection;
use Smile\ScopedEav\Model\Entity\Attribute\Backend\Image;

/**
 * Custom Entity EAV Setup.
 */
class CustomEntitySetup extends EavSetup
{
    /**
     * Installed EAV entities.
     *
     * @return array|null
     */
    public function getDefaultEntities(): ?array
    {
        return [
            'smile_custom_entity' => [
                'entity_model' => CustomEntityModel::class,
                'attribute_model' => Attribute::class,
                'table' => 'smile_custom_entity',
                'attributes' => $this->getDefaultAttributes(),
                'additional_attribute_table' => 'smile_custom_entity_eav_attribute',
                'entity_attribute_collection' => Collection::class,
            ],
        ];
    }

    /**
     * List of default attributes.
     */
    private function getDefaultAttributes(): array
    {
        return [
            'name' => [
                'type' => 'varchar',
                'label' => 'Name',
                'input' => 'text',
                'sort_order' => 1,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General',
            ],
            'is_active' => [
                'type' => 'int',
                'label' => 'Is Active',
                'input' => 'select',
                'source' => Boolean::class,
                'sort_order' => 2,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General',
            ],
            'url_key' => [
                'type' => 'varchar',
                'label' => 'URL Key',
                'input' => 'text',
                'required' => false,
                'sort_order' => 3,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General',
            ],
            'description' => [
                'type' => 'text',
                'label' => 'Description',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 4,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'wysiwyg_enabled' => true,
                'is_html_allowed_on_front' => true,
                'group' => 'General',
            ],
            'image' => [
                'type' => 'varchar',
                'label' => 'Image',
                'input' => 'image',
                'backend' => Image::class,
                'required' => false,
                'sort_order' => 5,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General',
            ],
        ];
    }
}
