<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api;

use Magento\Framework\Api\MetadataServiceInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException as InputExceptionAlias;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeSearchResultsInterface;

interface CustomEntityAttributeRepositoryInterface extends MetadataServiceInterface
{
    /**
     * Retrieve specific attribute.
     *
     * @param string $attributeCode Attribute code.
     * @return CustomEntityAttributeInterface|null
     * @throws NoSuchEntityException
     */
    public function get(string $attributeCode): ?CustomEntityAttributeInterface;

    /**
     * Save attribute data.
     *
     * @param CustomEntityAttributeInterface $attribute Attribute.
     * @return CustomEntityAttributeInterface|null
     * @throws NoSuchEntityException
     * @throws InputExceptionAlias
     * @throws StateException
     */
    public function save(CustomEntityAttributeInterface $attribute): ?CustomEntityAttributeInterface;

    /**
     * Delete Attribute.
     *
     * @param CustomEntityAttributeInterface $attribute Attribute.
     * @return bool True if the entity was deleted (always true)
     * @throws StateException
     * @throws NoSuchEntityException
     */
    public function delete(CustomEntityAttributeInterface $attribute): bool;

    /**
     * Delete Attribute by id
     *
     * @param string $attributeCode Attribute code.
     * @return bool
     * @throws StateException
     * @throws NoSuchEntityException
     */
    public function deleteById(string $attributeCode): bool;

    /**
     * Retrieve all attributes for entity type.
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria.
     * @return CustomEntityAttributeSearchResultsInterface|null
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ?CustomEntityAttributeSearchResultsInterface;
}
