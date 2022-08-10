<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\CustomEntity\AttributeSet;

use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity attribute set source model.
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
