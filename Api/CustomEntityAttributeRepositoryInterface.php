<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api;

/**
 * Custom entity attribute repository interface.
 *
 * @api
 */
interface CustomEntityAttributeRepositoryInterface extends \Magento\Framework\Api\MetadataServiceInterface
{
    /**
     * Retrieve specific attribute.
     *
     * @param string $attributeCode Attribute code.
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($attributeCode);

    /**
     * Save attribute data.
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface $attribute Attribute.
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function save(\Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface $attribute);

    /**
     * Delete Attribute.
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface $attribute Attribute.
     *
     * @return bool True if the entity was deleted (always true)
     *
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function delete(\Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface $attribute);

    /**
     * Delete Attribute by id
     *
     * @param string $attributeCode Attribute code.
     *
     * @return bool
     *
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function deleteById($attributeCode);

    /**
     * Retrieve all attributes for entity type.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria Search criteria.
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntityAttributeSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
