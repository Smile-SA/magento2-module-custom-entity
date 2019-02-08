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

namespace Smile\CustomEntity\Model\CustomEntity\AttributeSet;

use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity attribute set source model.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Options extends \Magento\Catalog\Model\Product\AttributeSet\Options
{
    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * Constructor.
     *
     * @param \Magento\Eav\Model\Config $eavConfig EAV Config.
     */
    public function __construct(\Magento\Eav\Model\Config $eavConfig)
    {
        $this->eavConfig = $eavConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function toOptionArray()
    {
        $entityType             = $this->eavConfig->getEntityType(CustomEntityInterface::ENTITY);
        $attributeSetCollection = $entityType->getAttributeSetCollection();

        return $attributeSetCollection->toOptionArray();
    }
}
