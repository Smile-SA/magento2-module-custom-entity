<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\CustomEntity
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\CustomEntity\Setup;

use Smile\ScopedEav\Model\Entity\Attribute\Backend\Image;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Smile\CustomEntity\Model\CustomEntity;
use Smile\CustomEntity\Model\CustomEntity\Attribute;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\Collection;

/**
 * Custom Entity EAV Setup.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @author   Maxime LECLERCQ <maxime.leclercq@smile.fr>
 */
class CustomEntitySetup extends \Magento\Eav\Setup\EavSetup
{
    /**
     * Installed EAV entities.
     *
     * @return array
     */
    public function getDefaultEntities()
    {
        return [
            'smile_custom_entity' => [
                'entity_model'                => CustomEntity::class,
                'attribute_model'             => Attribute::class,
                'table'                       => 'smile_custom_entity',
                'attributes'                  => $this->getDefaultAttributes(),
                'additional_attribute_table'  => 'smile_custom_entity_eav_attribute',
                'entity_attribute_collection' => Collection::class,
            ],
        ];
    }

    /**
     * List of default attributes.
     *
     * @return array
     */
    private function getDefaultAttributes()
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
            'description' => [
                'type' => 'text',
                'label' => 'Description',
                'input' => 'textarea',
                'required' => false,
                'sort_order' => 3,
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
                'sort_order' => 4,
                'global' => ScopedAttributeInterface::SCOPE_STORE,
                'group' => 'General',
            ],
        ];
    }
}
