<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Api\Data\CustomEntitySearchResultsInterface;

interface CustomEntityRepositoryInterface
{
    /**
     * Save a custom entity.
     *
     * @param CustomEntityInterface|null $entity Saved entity.
     * @return  CustomEntityInterface|null
     * @throws InputException
     * @throws StateException
     * @throws CouldNotSaveException
     */
    public function save(?CustomEntityInterface $entity): ?CustomEntityInterface;

    /**
     * Get custom entity by id.
     *
     * @param int      $entityId    Entity Id.
     * @param int|null $storeId     Store Id.
     * @param bool     $forceReload Force reload the entity..
     * @return CustomEntityInterface|null
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function get(int $entityId, ?int $storeId = null, bool $forceReload = false): ?CustomEntityInterface;

    /**
     * Delete custom entity.
     *
     * @param CustomEntityInterface $entity Deleted entity.
     * @return bool Will returned True if deleted
     * @throws StateException
     */
    public function delete(CustomEntityInterface $entity): bool;

    /**
     * Delete custom entity by id.
     *
     * @param int $entityId Deleted entity id.
     * @return bool Will returned True if deleted
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById(int $entityId): bool;

    /**
     * Get custom entity list.
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria.
     * @return CustomEntitySearchResultsInterface|SearchResults
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
