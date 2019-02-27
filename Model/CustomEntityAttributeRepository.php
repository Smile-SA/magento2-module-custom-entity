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

namespace Smile\CustomEntity\Model;

use Smile\CustomEntity\Api\CustomEntityAttributeRepositoryInterface;

/**
 * Custom entity repository implementation.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class CustomEntityAttributeRepository implements CustomEntityAttributeRepositoryInterface
{
    /**
     * @var \Magento\Eav\Api\AttributeRepositoryInterface
     */
    private $eavAttributeRepository;

    /**
     * Constructor.
     *
     * @param \Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository Base repository.
     */
    public function __construct(
        \Magento\Eav\Api\AttributeRepositoryInterface $eavAttributeRepository
    ) {
        $this->eavAttributeRepository = $eavAttributeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function get($attributeCode)
    {
        return $this->eavAttributeRepository->get($this->getEntityTypeCode(), $attributeCode);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(\Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface $attribute)
    {
        return $this->eavAttributeRepository->delete($attribute);
    }

    /**
     * {@inheritDoc}
     */
    public function deleteById($attributeCode)
    {
        if (!is_numeric($attributeCode)) {
            $attributeCode = $this->eavAttributeRepository->get($this->getEntityTypeCode(), $attributeCode)->getAttributeId();
        }

        return $this->eavAttributeRepository->deleteById($attributeCode);
    }

    /**
     * {@inheritDoc}
     */
    public function save(\Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface $attribute)
    {
        return $this->eavAttributeRepository->save($attribute);
    }

    /**
     * {@inheritDoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        // TODO: Implement getList() method.
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     * {@inheritDoc}
     */
    public function getCustomAttributesMetadata($dataObjectClassName = null)
    {
        // TODO: Implement getCustomAttributesMetadata() method.
        throw new \BadMethodCallException('Not implemented');
    }

    /**
     * Get the custom entity type code.
     *
     * @return string
     */
    private function getEntityTypeCode()
    {
        return \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface::ENTITY_TYPE_CODE;
    }
}
