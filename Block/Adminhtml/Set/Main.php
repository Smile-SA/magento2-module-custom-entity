<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Adminhtml\Set;

use Magento\Catalog\Model\Entity\Product\Attribute\Group\AttributeMapperInterface;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\CollectionFactory as AttributeCollectionFactory;

/**
 * Custom entity attribute set main form container.
 */
class Main extends \Smile\ScopedEav\Block\Adminhtml\Set\Main
{
    /**
     *
     * @param \Magento\Backend\Block\Template\Context                                  $context                    Block context.
     * @param \Magento\Framework\Json\EncoderInterface                                 $jsonEncoder                JSON encoder.
     * @param \Magento\Eav\Model\Entity\TypeFactory                                    $typeFactory                Entity type factory.
     * @param \Magento\Eav\Model\Entity\Attribute\GroupFactory                         $groupFactory               Attribute group factory.
     * @param \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory          Unused (compat only).
     * @param \Magento\Framework\Registry                                              $registry                   Registry.
     * @param AttributeMapperInterface                                                 $attributeMapper            Attribute mapper.
     * @param AttributeCollectionFactory                                               $attributeCollectionFactory Attribute collection
     *                                                                                                             factory.
     * @param array                                                                    $data                       Additional data.
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Eav\Model\Entity\TypeFactory $typeFactory,
        \Magento\Eav\Model\Entity\Attribute\GroupFactory $groupFactory,
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $collectionFactory,
        \Magento\Framework\Registry $registry,
        AttributeMapperInterface $attributeMapper,
        AttributeCollectionFactory $attributeCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $jsonEncoder, $typeFactory, $groupFactory, $collectionFactory, $registry, $attributeMapper, $data);

        $this->_collectionFactory = $attributeCollectionFactory;
    }
}
