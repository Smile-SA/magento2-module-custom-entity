<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model;

use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Framework\Api\AttributeValueFactory;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Api\MetadataServiceInterface;
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Smile\CustomEntity\Api\Data\CustomEntityExtensionInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\ScopedEav\Model\AbstractEntity;

/**
 * Custom entity model default implementation.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomEntity extends AbstractEntity implements IdentityInterface, CustomEntityInterface
{
    /**
     * Product cache tag
     */
    const CACHE_TAG = 'smile_custom_entity';

    const CACHE_CUSTOM_ENTITY_SET_TAG = 'smile_custom_entity_set';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * @var string
     */
    protected $_eventPrefix = 'smile_custom_entity';

    /**
     * @var string
     */
    protected $_eventObject = 'smile_custom_entity';

    /**
     * @var MetadataServiceInterface
     */
    private $metadataService;

    /**
     * List of attributes defined in the interface.
     *
     * @var string[]
     */
    private $interfaceAttributes = [
        self::ATTRIBUTE_SET_ID,
        self::CREATED_AT,
        self::UPDATED_AT,
        self::NAME,
        self::IS_ACTIVE,
    ];

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var AttributeSetRepositoryInterface
     */
    private $attributeSetRepository;

    /**
     * @var AttributeSetInterface
     */
    private $attributeSet;

    /**
     * Constructor.
     *
     * @param Context $context Context.
     * @param Registry $registry Registry.
     * @param ExtensionAttributesFactory $extensionFactory Extension factory.
     * @param AttributeValueFactory $customAttributeFactory Custom attribute factory.
     * @param StoreManagerInterface $storeManager Store manager.
     * @param MetadataServiceInterface $metadataService Metadata service.
     * @param FilterManager $filterManager Filter Manager.
     * @param AttributeSetRepositoryInterface $attributeSetRepository Attribute set repository.
     * @param AbstractResource|null $resource Resource model.
     * @param AbstractDb|null $resourceCollection Collection.
     * @param array $data Additional data.
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        StoreManagerInterface $storeManager,
        MetadataServiceInterface $metadataService,
        FilterManager $filterManager,
        AttributeSetRepositoryInterface $attributeSetRepository,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $storeManager,
            $resource,
            $resourceCollection,
            $data
        );
        $this->metadataService = $metadataService;
        $this->filterManager = $filterManager;
        $this->attributeSetRepository = $attributeSetRepository;
    }

    /**
     * Get identities
     *
     * @return array|null
     */
    public function getIdentities(): ?array
    {
        $identities = [self::CACHE_TAG . '_' . $this->getId()];
        if ($this->dataHasChangedFor(self::IS_ACTIVE) && $this->getIsActive()) {
            $identities[] = self::CACHE_CUSTOM_ENTITY_SET_TAG . '_' . $this->getAttributeSetId();
        }

        return array_unique($identities);
    }

    /**
     * Get url key
     *
     * @return string|null
     */
    public function getUrlKey(): ?string
    {
        $urlKey = $this->getData(self::URL_KEY);
        if ($urlKey === '' || $urlKey === null) {
            $urlKey = $this->filterManager->translitUrl($this->getName());
        }

        return $urlKey;
    }

    /**
     * Set url key.
     *
     * @param string $urlKey Url key
     *
     * @return $this
     */
    public function setUrlKey(string $urlKey): self
    {
        return $this->setData(self::URL_KEY, $urlKey);
    }

    /**
     * Get url path.
     *
     * @return string
     */
    public function getUrlPath(): string
    {
        return $this->getAttributeSetUrlKey() . '/' . $this->getUrlKey();
    }

    /**
     * Return attribute set.
     *
     * @return AttributeSetInterface|null
     * @throws NoSuchEntityException
     */
    public function getAttributeSet(): ?AttributeSetInterface
    {
        if (!$this->attributeSet) {
            $this->attributeSet = $this->attributeSetRepository->get($this->getAttributeSetId());
        }

        return $this->attributeSet;
    }

    /**
     * Return attribute set url key.
     *
     * @return string|null
     * @throws NoSuchEntityException
     */
    public function getAttributeSetUrlKey(): ?string
    {
        $attributeSetName = $this->getAttributeSet()->getAttributeSetName();

        return $this->filterManager->translitUrl($attributeSetName);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave()
    {
        $urlKey = $this->getData(self::URL_KEY);
        if ($urlKey === '' || $urlKey === null) {
            $this->setUrlKey($this->filterManager->translitUrl($this->getName()));
        }

        return parent::beforeSave();
    }

    /**
     * Validate Product Data
     *
     * @return true|array
     */
    public function validate()
    {
        $this->_eventManager->dispatch($this->_eventPrefix . '_validate_before', $this->_getEventData());
        $result = $this->_getResource()->validate($this);
        $this->_eventManager->dispatch($this->_eventPrefix . '_validate_after', $this->_getEventData());

        return $result;
    }

    /**
     * Retrieve product websites identifiers
     *
     * @return array|null
     */
    public function getWebsiteIds(): ?array
    {
        if (!$this->hasWebsiteIds()) {
            $ids = $this->_getResource()->getWebsiteIds($this);
            $this->setWebsiteIds($ids);
        }

        return $this->getData('website_ids');
    }

    /**
     * Get all sore ids where product is presented
     *
     * @return array|null
     */
    public function getStoreIds(): ?array
    {
        if (!$this->hasStoreIds()) {
            $storeIds = [];
            if ($websiteIds = $this->getWebsiteIds()) {
                foreach ($websiteIds as $websiteId) {
                    $websiteStores = $this->_storeManager->getWebsite($websiteId)->getStoreIds();
                    $storeIds = array_merge($storeIds, $websiteStores);
                }
            }
            $this->setStoreIds($storeIds);
        }

        return $this->getData('store_ids');
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return CustomEntityExtensionInterface|null
     */
    public function getExtensionAttributes(): ?CustomEntityExtensionInterface
    {
        $extensionAttributes = $this->_getExtensionAttributes();
        if (!$extensionAttributes) {
            return $this->extensionAttributesFactory->create(CustomEntityInterface::class);
        }

        return $extensionAttributes;
    }

    /**
     * Set an extension attributes object.
     *
     * @param CustomEntityExtensionInterface $extensionAttributes Extension attributes.
     * @return $this
     */
    public function setExtensionAttributes(CustomEntityExtensionInterface $extensionAttributes): self
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Returns custom entity ids match with url key and attribute set id.
     *
     * @param string   $urlKey         Url key.
     * @param null|int $attributeSetId Attribute set id.
     *
     * @return mixed
     * @throws NoSuchEntityException
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function checkIdentifier(string $urlKey, ?int $attributeSetId = null)
    {
        $collection = $this->getCollection()
            ->addFieldToSelect('entity_id')
            ->addFieldToFilter('url_key', $urlKey)
            ->addFieldToFilter('is_active', 1)
            ->setPageSize(1);
        if ($attributeSetId !== null) {
            $collection->addFieldToFilter('attribute_set_id', $attributeSetId);
        }
        if (!$collection->getSize()) {
            throw NoSuchEntityException::doubleField('url_key', $urlKey, 'attribute_set_id', $attributeSetId);
        }

        // @codingStandardsIgnoreLine add setPageSize MEQP1.Performance.InefficientMethods.FoundGetFirstItem
        return $collection->getFirstItem()->getId();
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct(): void
    {
        $this->_init(\Smile\CustomEntity\Model\ResourceModel\CustomEntity::class);
    }

    /**
     * {@inheritdoc}
     */
    protected function getCustomAttributesCodes()
    {
        if ($this->customAttributesCodes === null) {
            $this->customAttributesCodes = $this->getEavAttributesCodes($this->metadataService);
            $this->customAttributesCodes = array_diff($this->customAttributesCodes, $this->interfaceAttributes);
        }

        return $this->customAttributesCodes;
    }
}
