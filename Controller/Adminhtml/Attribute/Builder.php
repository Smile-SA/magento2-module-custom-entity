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

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;

/**
 * Custom entity attribute builder
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Builder extends \Smile\ScopedEav\Controller\Adminhtml\Attribute\AbstractBuilder
{
    /**
     * @var \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterfaceFactory
     */
    private $attributeFactory;

    /**
     * @var \Smile\CustomEntity\Api\CustomEntityAttributeRepositoryInterface
     */
    private $attributeRepository;

    /**
     *
     * @param \Magento\Framework\Registry                                                    $registry            Registry.
     * @param \Magento\Eav\Model\Config                                                      $eavConfig           EAV config.
     * @param \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterfaceFactory $attributeFactory    Attribute factory.
     * @param \Smile\CustomEntity\Api\CustomEntityAttributeRepositoryInterface   $attributeRepository Attribute repository.
     */
    public function __construct(
        \Magento\Framework\Registry $registry,
        \Magento\Eav\Model\Config $eavConfig,
        \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterfaceFactory $attributeFactory,
        \Smile\CustomEntity\Api\CustomEntityAttributeRepositoryInterface $attributeRepository
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
