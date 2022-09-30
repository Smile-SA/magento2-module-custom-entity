<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

use Magento\Eav\Model\Config;
use Magento\Framework\Registry;
use Psr\Log\LoggerInterface;
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
     * @param LoggerInterface $logger Logger.
     * @param Registry $registry Registry.
     * @param Config $eavConfig EAV config.
     * @param CustomEntityAttributeInterfaceFactory $attributeFactory Attribute factory.
     * @param CustomEntityAttributeRepositoryInterface $attributeRepository Attribute repository.
     */
    public function __construct(
        LoggerInterface $logger,
        Registry $registry,
        Config $eavConfig,
        CustomEntityAttributeInterfaceFactory $attributeFactory,
        CustomEntityAttributeRepositoryInterface $attributeRepository
    ) {
        parent::__construct($logger, $registry, $eavConfig);

        $this->attributeFactory    = $attributeFactory;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributeFactory()
    {
        return $this->attributeFactory;
    }

    /**
     * @inheritdoc
     */
    protected function getAttributeRepository()
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
