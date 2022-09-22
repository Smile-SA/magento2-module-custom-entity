<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

use Magento\Eav\Model\Config;
use Magento\Framework\Registry;
use Smile\CustomEntity\Api\CustomEntityAttributeRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterfaceFactory;
use Smile\ScopedEav\Controller\Adminhtml\Attribute\AbstractBuilder;

/**
 * Custom entity attribute builder
 */
class Builder extends AbstractBuilder
{
    private CustomEntityAttributeInterfaceFactory $attributeFactory;

    private CustomEntityAttributeRepositoryInterface $attributeRepository;

    /**
     * Constructor.
     *
     * @param Registry $registry Registry.
     * @param Config $eavConfig EAV config.
     * @param CustomEntityAttributeInterfaceFactory $attributeFactory Attribute factory.
     * @param CustomEntityAttributeRepositoryInterface $attributeRepository Attribute repository.
     */
    public function __construct(
        Registry $registry,
        Config $eavConfig,
        CustomEntityAttributeInterfaceFactory $attributeFactory,
        CustomEntityAttributeRepositoryInterface $attributeRepository
    ) {
        parent::__construct($registry, $eavConfig);

        $this->attributeFactory    = $attributeFactory;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributeFactory(): CustomEntityAttributeInterfaceFactory
    {
        return $this->attributeFactory;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributeRepository(): CustomEntityAttributeRepositoryInterface
    {
        return $this->attributeRepository;
    }

    /**
     * @inheritdoc
     */
    protected function getEntityTypeCode(): string
    {
        return CustomEntityAttributeInterface::ENTITY_TYPE_CODE;
    }
}
