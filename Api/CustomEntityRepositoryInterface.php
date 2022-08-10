<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api;

/**
 * Custom entity repository interface.
 *
 * @api
 */
interface CustomEntityRepositoryInterface
{
    /**
     * Save a custom entity.
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityInterface $entity Saved entity.
     *
     * @return \\Smile\CustomEntity\Api\Data\CustomEntityInterface
     *
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Smile\CustomEntity\Api\Data\CustomEntityInterface $entity);

    /**
     * Get custom entity by id.
     *
     * @param int      $entityId    Entity Id.
     * @param int|null $storeId     Store Id.
     * @param bool     $forceReload Force reload the entity..
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntityInterface
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     */
    public function get($entityId, $storeId = null, $forceReload = false);

    /**
     * Delete custom entity.
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityInterface $entity Deleted entity.
     *
     * @return bool Will returned True if deleted
     *
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(\Smile\CustomEntity\Api\Data\CustomEntityInterface $entity);

    /**
     * Delete custom entity by id.
     *
     * @param int $entityId Deleted entity id.
     *
     * @return bool Will returned True if deleted
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($entityId);

    /**
     * Get custom entity list.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria Search criteria.
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntitySearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}
