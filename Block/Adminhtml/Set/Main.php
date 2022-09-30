<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Adminhtml\Set;

use Magento\Backend\Block\Template\Context;
use Magento\Catalog\Model\Entity\Product\Attribute\Group\AttributeMapperInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;
use Magento\Eav\Model\Entity\Attribute\GroupFactory;
use Magento\Eav\Model\Entity\TypeFactory;
use Magento\Framework\Json\EncoderInterface;
use Magento\Framework\Registry;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\CollectionFactory as AttributeCollectionFactory;

/**
 * Custom entity attribute set main form container.
 */
class Main extends \Smile\ScopedEav\Block\Adminhtml\Set\Main
{
    /**
     * @param Context $context Block context.
     * @param EncoderInterface $jsonEncoder JSON encoder.
     * @param TypeFactory $typeFactory Entity type factory.
     * @param GroupFactory $groupFactory Attribute group factory.
     * @param CollectionFactory $collectionFactory Unused (compat only).
     * @param Registry $registry Registry.
     * @param AttributeMapperInterface $attributeMapper Attribute mapper.
     * @param AttributeCollectionFactory $attributeCollectionFactory Attribute collection factory.
     * @param array $data Additional data.
     */
    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        TypeFactory $typeFactory,
        GroupFactory $groupFactory,
        CollectionFactory $collectionFactory,
        Registry $registry,
        AttributeMapperInterface $attributeMapper,
        AttributeCollectionFactory $attributeCollectionFactory,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $jsonEncoder,
            $typeFactory,
            $groupFactory,
            $collectionFactory,
            $registry,
            $attributeMapper,
            $data
        );

        /** @var CollectionFactory $attributeCollectionFactory */
        $this->_collectionFactory = $attributeCollectionFactory;
    }
}
