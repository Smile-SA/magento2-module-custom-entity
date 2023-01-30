<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model;

use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SearchResults;
use Magento\Framework\DataObject;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\InputException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\StoreManagerInterface;
use Smile\CustomEntity\Api\CustomEntityRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface as CustomEntityInterfaceData;
use Smile\CustomEntity\Api\Data\CustomEntitySearchResultsInterface;
use Smile\CustomEntity\Api\Data\CustomEntitySearchResultsInterfaceFactory;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity as CustomEntityResource;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\CollectionFactory as CustomEntityCollectionFactory;

/**
 * Custom entity repository implementation.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects) getList method require multiple class.
 */
class CustomEntityRepository implements CustomEntityRepositoryInterface
{
    /**
     * @var CustomEntityInterface[]
     */
    protected array $instances = [];

    private CustomEntityResource $customEntityResource;

    private CustomEntityFactory $customEntityFactory;

    private StoreManagerInterface $storeManager;

    private MetadataPool $metadataPool;

    private ExtensibleDataObjectConverter $extensibleDataObjectConverter;

    private CustomEntityCollectionFactory $customEntityCollectionFactory;

    private CollectionProcessorInterface $collectionProcessor;

    private CustomEntitySearchResultsInterfaceFactory $customEntitySearchResultsFactory;

    private JoinProcessorInterface $joinProcessor;

    /**
     * Constructor.
     *
     * @param CustomEntityFactory $customEntityFactory Custom entity factory.
     * @param CustomEntityResource $customEntityResource Custom entity resource model.
     * @param StoreManagerInterface $storeManager Store manager.
     * @param MetadataPool $metadataPool Metadata pool.
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter Converter.
     * @param CustomEntityCollectionFactory $customEntityCollectionFactory Custom entity collection factory.
     * @param CollectionProcessorInterface $collectionProcessor Search criteria collection processor.
     * @param JoinProcessorInterface $joinProcessor Extension attriubute join processor.
     * @param CustomEntitySearchResultsInterfaceFactory $customEntitySearchResultsFactory Custom entity search.
     */
    public function __construct(
        CustomEntityFactory $customEntityFactory,
        CustomEntityResource $customEntityResource,
        StoreManagerInterface $storeManager,
        MetadataPool $metadataPool,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter,
        CustomEntityCollectionFactory $customEntityCollectionFactory,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $joinProcessor,
        CustomEntitySearchResultsInterfaceFactory $customEntitySearchResultsFactory
    ) {
        $this->customEntityFactory = $customEntityFactory;
        $this->customEntityResource = $customEntityResource;
        $this->storeManager = $storeManager;
        $this->metadataPool = $metadataPool;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->customEntityCollectionFactory = $customEntityCollectionFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->joinProcessor = $joinProcessor;
        $this->customEntitySearchResultsFactory = $customEntitySearchResultsFactory;
    }

    /**
     * Save a custom entity.
     *
     * @param CustomEntityInterface|null $entity Saved entity.
     * @throws InputException
     * @throws StateException
     * @throws CouldNotSaveException
     */
    public function save(?CustomEntityInterface $entity): ?CustomEntityInterface
    {
        $existingData = $this->extensibleDataObjectConverter->toNestedArray($entity, [], CustomEntityInterface::class);

        if (!isset($existingData['store_id'])) {
            $existingData['store_id'] = (int) $this->storeManager->getStore()->getId();
        }

        $storeId = $existingData['store_id'];

        if ($entity->getId()) {
            $metadata = $this->metadataPool->getMetadata(CustomEntityInterface::class);

            /** @var DataObject $entity */
            $entity = $this->get($entity->getId(), $storeId);
            $existingData[$metadata->getLinkField()] = $entity->getData($metadata->getLinkField());
        }
        $entity->addData($existingData);

        try {
            /** @var AbstractModel $entity */
            $this->customEntityResource->save($entity);
        } catch (CouldNotSaveException $e) {
            throw new CouldNotSaveException(__('Could not save entity: %1', $e->getMessage()), $e);
        }
        unset($this->instances[$entity->getId()]);

        return $this->get($entity->getId(), $storeId);
    }

    /**
     * Get custom entity by id.
     *
     * @param int|string $entityId Entity Id.
     * @param int|null $storeId Store Id.
     * @param bool $forceReload Force reload the entity..
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function get($entityId, ?int $storeId = null, bool $forceReload = false): ?CustomEntityInterface
    {
        $cacheKey = $storeId ?? 'all';

        if (!isset($this->instances[$entityId][$cacheKey]) || $forceReload === true) {
            $entity = $this->customEntityFactory->create();
            if (null !== $storeId) {
                $entity->setStoreId($storeId);
            }
            $entity->load($entityId);
            if (!$entity->getId()) {
                throw NoSuchEntityException::singleField('id', $entityId);
            }
            $this->instances[$entityId][$cacheKey] = $entity;
        }

        return $this->instances[$entityId][$cacheKey];
    }

    /**
     * Delete custom entity.
     *
     * @param CustomEntityInterface $entity Deleted entity.
     * @return bool Will returned True if deleted
     * @throws StateException
     */
    public function delete(CustomEntityInterface $entity): bool
    {
        try {
            $this->customEntityResource->delete($entity);
        } catch (\Exception $e) {
            throw new StateException(__('Cannot delete entity with id %1', $entity->getId()), $e);
        }

        unset($this->instances[$entity->getId()]);

        return true;
    }

    /**
     * Delete custom entity by id.
     *
     * @param int $entityId Deleted entity id.
     * @return bool Will returned True if deleted
     * @throws NoSuchEntityException
     * @throws StateException
     */
    public function deleteById(int $entityId): bool
    {
        $entity = $this->get($entityId);

        return $this->delete($entity);
    }

    /**
     * Get custom entity list.
     *
     * @param SearchCriteriaInterface $searchCriteria Search criteria.
     * @return CustomEntitySearchResultsInterface|SearchResults
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Smile\CustomEntity\Model\ResourceModel\CustomEntity\Collection $collection */
        $collection = $this->customEntityCollectionFactory->create();
        $this->joinProcessor->process($collection);
        $collection->addAttributeToSelect('*');
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var CustomEntitySearchResultsInterface $searchResults */
        $searchResults = $this->customEntitySearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        /** @var CustomEntityInterfaceData[] $collectionItems */
        $collectionItems = $collection->getItems();
        $searchResults->setItems($collectionItems);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
