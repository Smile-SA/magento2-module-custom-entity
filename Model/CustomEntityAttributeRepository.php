<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model;

use BadMethodCallException;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\InputException as InputExceptionAlias;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Smile\CustomEntity\Api\CustomEntityAttributeRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeSearchResultsInterface;

/**
 * Custom entity repository implementation.
 */
class CustomEntityAttributeRepository implements CustomEntityAttributeRepositoryInterface
{
    private AttributeRepositoryInterface $eavAttributeRepository;

    /**
     * Constructor.
     *
     * @param AttributeRepositoryInterface $eavAttributeRepository Base repository.
     */
    public function __construct(
        AttributeRepositoryInterface $eavAttributeRepository
    ) {
        $this->eavAttributeRepository = $eavAttributeRepository;
    }

    /**
     * Retrieve specific attribute.
     *
     * @param string $attributeCode Attribute code.
     * @throws NoSuchEntityException
     */
    public function get(string $attributeCode): ?CustomEntityAttributeInterface
    {
        /** @var CustomEntityAttributeInterface $customEntityAttribute */
        $customEntityAttribute = $this->eavAttributeRepository->get($this->getEntityTypeCode(), $attributeCode);
        return $customEntityAttribute;
    }

    /**
     * Delete Attribute.
     *
     * @param CustomEntityAttributeInterface $attribute Attribute.
     * @return bool True if the entity was deleted (always true)
     * @throws StateException
     * @throws NoSuchEntityException
     */
    public function delete(CustomEntityAttributeInterface $attribute): bool
    {
        return $this->eavAttributeRepository->delete($attribute);
    }

    /**
     * Delete Attribute by id
     *
     * @param string $attributeCode Attribute code.
     * @throws StateException
     * @throws NoSuchEntityException
     */
    public function deleteById(string $attributeCode): bool
    {
        if (!is_numeric($attributeCode)) {
            $attributeCode = $this->eavAttributeRepository->get(
                $this->getEntityTypeCode(),
                $attributeCode
            )->getAttributeId();
        }

        return $this->eavAttributeRepository->deleteById($attributeCode);
    }

    /**
     * Save attribute data.
     *
     * @param CustomEntityAttributeInterface $attribute Attribute.
     * @throws NoSuchEntityException
     * @throws InputExceptionAlias
     * @throws StateException
     */
    public function save(CustomEntityAttributeInterface $attribute): ?CustomEntityAttributeInterface
    {
        /** @var CustomEntityAttributeInterface $customEntityAttribute */
        $customEntityAttribute = $this->eavAttributeRepository->save($attribute);
        return $customEntityAttribute;
    }

    /**
     * Retrieve all attributes for entity type.
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria.
     */
    public function getList(SearchCriteriaInterface $searchCriteria): ?CustomEntityAttributeSearchResultsInterface
    {
        // TODO: Implement getList() method.
        throw new BadMethodCallException('Not implemented');
    }

    /**
     * @inheritdoc
     */
    public function getCustomAttributesMetadata($dataObjectClassName = null)
    {
        // TODO: Implement getCustomAttributesMetadata() method.
        throw new BadMethodCallException('Not implemented');
    }

    /**
     * Get the custom entity type code.
     */
    private function getEntityTypeCode(): string
    {
        return CustomEntityAttributeInterface::ENTITY_TYPE_CODE;
    }
}
