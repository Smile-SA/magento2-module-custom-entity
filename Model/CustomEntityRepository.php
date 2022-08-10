<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model;

use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\StateException;
use Magento\Store\Model\StoreManagerInterface;
use Smile\CustomEntity\Api\CustomEntityRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Api\Data\CustomEntitySearchResultsInterface;
use Smile\CustomEntity\Api\Data\CustomEntitySearchResultsInterfaceFactory;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use \Smile\CustomEntity\Model\ResourceModel\CustomEntity as CustomEntityResource;
use \Smile\CustomEntity\Model\ResourceModel\CustomEntity\CollectionFactory as CustomEntityCollectionFactory;

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
    protected $instances = [];

    /**
     * @var CustomEntityResource
     */
    private $customEntityResource;

    /**
     * @var CustomEntityFactory
     */
    private $customEntityFactory;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var MetadataPool
     */
    private $metadataPool;

    /**
     * @var ExtensibleDataObjectConverter
     */
    private $extensibleDataObjectConverter;

    /**
     * @var CustomEntityCollectionFactory
     */
    private $customEntityCollectionFactory;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var CustomEntitySearchResultsInterfaceFactory
     */
    private $customEntitySearchResultsFactory;

    /**
     * @var JoinProcessorInterface
     */
    private $joinProcessor;

    /**
     * Constructor.
     *
     * @param CustomEntityFactory                       $customEntityFactory              Custom entity factory.
     * @param CustomEntityResource                      $customEntityResource             Custom entity resource model.
     * @param StoreManagerInterface                     $storeManager                     Store manager.
     * @param MetadataPool                              $metadataPool                     Metadata pool.
     * @param ExtensibleDataObjectConverter             $extensibleDataObjectConverter    Converter.
     * @param CustomEntityCollectionFactory             $customEntityCollectionFactory    Custom entity collection
     *                                                                                    factory.
     * @param CollectionProcessorInterface              $collectionProcessor              Search criteria collection
     *                                                                                    processor.
     * @param JoinProcessorInterface                    $joinProcessor                    Extension attriubute join
     *                                                                                    processor.
     * @param CustomEntitySearchResultsInterfaceFactory $customEntitySearchResultsFactory Custom entity search results
     *                                                                                    factory.
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
        $this->customEntityFactory           = $customEntityFactory;
        $this->customEntityResource          = $customEntityResource;
        $this->storeManager                  = $storeManager;
        $this->metadataPool                  = $metadataPool;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
        $this->customEntityCollectionFactory = $customEntityCollectionFactory;
        $this->collectionProcessor           = $collectionProcessor;
        $this->joinProcessor = $joinProcessor;
        $this->customEntitySearchResultsFactory = $customEntitySearchResultsFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function save(\Smile\CustomEntity\Api\Data\CustomEntityInterface $entity)
    {
        $existingData = $this->extensibleDataObjectConverter->toNestedArray($entity, [], CustomEntityInterface::class);

        if (!isset($existingData['store_id'])) {
            $existingData['store_id'] = (int) $this->storeManager->getStore()->getId();
        }

        $storeId = $existingData['store_id'];

        if ($entity->getId()) {
            $metadata = $this->metadataPool->getMetadata(CustomEntityInterface::class);

            $entity = $this->get($entity->getId(), $storeId);
            $existingData[$metadata->getLinkField()] = $entity->getData($metadata->getLinkField());
        }
        $entity->addData($existingData);

        try {
            $this->customEntityResource->save($entity);
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__('Could not save entity: %1', $e->getMessage()), $e);
        }
        unset($this->instances[$entity->getId()]);

        return $this->get($entity->getId(), $storeId);
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.BooleanArgumentFlag)
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function get($entityId, $storeId = null, $forceReload = false)
    {
        $cacheKey = null !== $storeId ? $storeId : 'all';

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
     * {@inheritdoc}
     */
    public function delete(\Smile\CustomEntity\Api\Data\CustomEntityInterface $entity)
    {
        try {
            $entityId = $entity->getId();
            $this->customEntityResource->delete($entity);
        } catch (\Exception $e) {
            throw new StateException(__('Cannot delete entity with id %1', $entity->getId()), $e);
        }

        unset($this->instances[$entityId]);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($entityId)
    {
        $entity = $this->get($entityId);

        return $this->delete($entity);
    }

    /**
     * {@inheritDoc}
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        /** @var \Smile\CustomEntity\Model\ResourceModel\CustomEntity\Collection $collection */
        $collection = $this->customEntityCollectionFactory->create();
        $this->joinProcessor->process($collection);
        $collection->addAttributeToSelect('*');
        $this->collectionProcessor->process($searchCriteria, $collection);

        /** @var CustomEntitySearchResultsInterface $searchResults */
        $searchResults = $this->customEntitySearchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }
}
