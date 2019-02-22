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

use Magento\Framework\DataObject\IdentityInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity model default implementation.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class CustomEntity extends \Smile\ScopedEav\Model\AbstractEntity implements IdentityInterface, CustomEntityInterface
{
    /**
     * Product cache tag
     */
    const CACHE_TAG = 'smile_custom_entity';

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
     * @var \Magento\Framework\Api\MetadataServiceInterface
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
        self::IMAGE,
        self::DESCRIPTION,
    ];

    /**
     * Constructor.
     *
     * @param \Magento\Framework\Model\Context                             $context                Context.
     * @param \Magento\Framework\Registry                                  $registry               Registry.
     * @param \Magento\Framework\Api\ExtensionAttributesFactory            $extensionFactory       Extension factory.
     * @param \Magento\Framework\Api\AttributeValueFactory                 $customAttributeFactory Custom attribute factory.
     * @param \Magento\Store\Model\StoreManagerInterface                   $storeManager           Store manager.
     * @param \Magento\Framework\Api\MetadataServiceInterface              $metadataService        Metadata service.
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource               Resource model.
     * @param \Magento\Framework\Data\Collection\AbstractDb|null           $resourceCollection     Collection.
     * @param array                                                        $data                   Additional data.
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        \Magento\Framework\Api\AttributeValueFactory $customAttributeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Api\MetadataServiceInterface $metadataService,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
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
    }

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities()
    {
        $identities = [self::CACHE_TAG . '_' . $this->getId()];

        return array_unique($identities);
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
     * @return array
     */
    public function getWebsiteIds()
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
     * @return array
     */
    public function getStoreIds()
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
     * {@inheritdoc}
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntityExtensionInterface $extensionAttributes\null
     */
    public function getExtensionAttributes()
    {
        $extensionAttributes = $this->_getExtensionAttributes();
        if (!$extensionAttributes) {
            return $this->extensionAttributesFactory->create(CustomEntityInterface::class);
        }

        return $extensionAttributes;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(\Smile\CustomEntity\Api\Data\CustomEntityExtensionInterface $extensionAttributes)
    {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
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
