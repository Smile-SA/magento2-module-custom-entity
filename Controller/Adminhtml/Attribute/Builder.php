<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

use Smile\CustomEntity\Api\CustomEntityAttributeRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterfaceFactory;

/**
 * Custom entity attribute builder
 */
class Builder extends \Smile\ScopedEav\Controller\Adminhtml\Attribute\AbstractBuilder
{
    /**
     * @var CustomEntityAttributeInterfaceFactory
     */
    private $attributeFactory;

    /**
     * @var CustomEntityAttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\Registry              $registry            Registry.
     * @param \Magento\Eav\Model\Config                $eavConfig           EAV config.
     * @param CustomEntityAttributeInterfaceFactory    $attributeFactory    Attribute factory.
     * @param CustomEntityAttributeRepositoryInterface $attributeRepository Attribute repository.
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Eav\Model\Config $eavConfig,
        CustomEntityAttributeInterfaceFactory $attributeFactory,
        CustomEntityAttributeRepositoryInterface $attributeRepository
    ) {
        parent::__construct($registry, $eavConfig);

        $this->attributeFactory    = $attributeFactory;
        $this->attributeRepository = $attributeRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeFactory()
    {
        return $this->attributeFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function getAttributeRepository()
    {
        return $this->attributeRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function getEntityTypeCode()
    {
        return CustomEntityAttributeInterface::ENTITY_TYPE_CODE;
    }
}
