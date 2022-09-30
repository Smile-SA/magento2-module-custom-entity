<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\CustomEntity\AttributeSet;

use Magento\Eav\Model\Config;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity attribute set source model.
 */
class Options extends \Magento\Catalog\Model\Product\AttributeSet\Options
{
    private Config $eavConfig;

    /**
     * Constructor.
     *
     * @param Config $eavConfig EAV Config.
     */
    public function __construct(Config $eavConfig)
    {
        $this->eavConfig = $eavConfig;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray()
    {
        $entityType = $this->eavConfig->getEntityType(CustomEntityInterface::ENTITY);
        $attributeSetCollection = $entityType->getAttributeSetCollection();
        return $attributeSetCollection->toOptionArray();
    }
}
