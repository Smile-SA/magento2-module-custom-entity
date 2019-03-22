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

namespace Smile\CustomEntity\Model\ResourceModel;

use Magento\Eav\Model\Entity\Context;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Custom entity resource model.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class CustomEntity extends \Smile\ScopedEav\Model\ResourceModel\AbstractResource
{
    /**
     * @var string
     */
    protected $customEntityWebsiteTable;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * CustomEntity constructor.
     *
     * @param Context                                                   $context           Context.
     * @param \Magento\Framework\EntityManager\EntityManager            $entityManager     Entity manager.
     * @param \Magento\Eav\Model\Entity\TypeFactory                     $typeFactory       Type factory.
     * @param \Magento\Eav\Model\Entity\Attribute\SetFactory            $setFactory        Attribute set factory.
     * @param \Smile\ScopedEav\Model\Entity\Attribute\DefaultAttributes $defaultAttributes Default attributes.
     * @param StoreManagerInterface                                     $storeManager      Store manager.
     * @param array                                                     $data              Data.
     */
    public function __construct(
        Context $context,
        \Magento\Framework\EntityManager\EntityManager $entityManager,
        \Magento\Eav\Model\Entity\TypeFactory $typeFactory,
        \Magento\Eav\Model\Entity\Attribute\SetFactory $setFactory,
        \Smile\ScopedEav\Model\Entity\Attribute\DefaultAttributes $defaultAttributes,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        parent::__construct($context, $entityManager, $typeFactory, $setFactory, $defaultAttributes, $data);
        $this->storeManager = $storeManager;
    }


    /**
     * {@inheritdoc}
     */
    public function getEntityType()
    {
        if (empty($this->_type)) {
            $this->setType(\Smile\CustomEntity\Model\CustomEntity::ENTITY);
        }

        return parent::getEntityType();
    }

    /**
     * Custom entity website table name getter.
     *
     * @return string
     */
    public function getCustomEntityWebsiteTable()
    {
        if (!$this->customEntityWebsiteTable) {
            $this->customEntityWebsiteTable = $this->getTable('smile_custom_entity_website');
        }

        return $this->customEntityWebsiteTable;
    }

    /**
     * Retrieve custom entity website identifiers
     *
     * @param \Smile\CustomEntity\Model\CustomEntity|int $entity Custom entity.
     *
     * @return array
     */
    public function getWebsiteIds($entity)
    {
        $connection = $this->getConnection();

        $entityId = $entity;

        if ($entity instanceof \Smile\CustomEntity\Model\CustomEntity) {
            $entityId = $entity->getEntityId();
        }

        $select = $connection->select()->from(
            $this->getCustomEntityWebsiteTable(),
            'website_id'
        )->where(
            'entity_id = ?',
            (int) $entityId
        );

        return $connection->fetchCol($select);
    }

    /**
     * {@inheritdoc}
     */
    protected function _afterSave(\Magento\Framework\DataObject $entity)
    {
        $this->saveWebsiteIds($entity);

        return parent::_afterSave($entity);
    }

    /**
     * Save entity website relations
     *
     * @param \Smile\CustomEntity\Model\CustomEntity $entity Entity.
     *
     * @return $this
     */
    protected function saveWebsiteIds($entity)
    {
        if ($this->storeManager->isSingleStoreMode()) {
            $websiteId = $this->storeManager->getDefaultStoreView()->getWebsiteId();
            $entity->setWebsiteIds([$websiteId]);
        }
        $websiteIds = $entity->getWebsiteIds();

        $entity->setIsChangedWebsites(false);

        $connection = $this->getConnection();

        $oldWebsiteIds = $this->getWebsiteIds($entity);

        $insert = array_diff($websiteIds, $oldWebsiteIds);
        $delete = array_diff($oldWebsiteIds, $websiteIds);

        if (!empty($insert)) {
            $data = [];
            foreach ($insert as $websiteId) {
                $data[] = ['entity_id' => (int) $entity->getEntityId(), 'website_id' => (int) $websiteId];
            }
            $connection->insertMultiple($this->getCustomEntityWebsiteTable(), $data);
        }

        if (!empty($delete)) {
            $websiteIdsForDelete = [];
            foreach ($delete as $websiteId) {
                $websiteIdsForDelete[] = (int) $websiteId;
            }
            $condition = ['entity_id = ?' => (int) $entity->getEntityId(), 'website_id in (?)' => $websiteIdsForDelete];
            $connection->delete($this->getCustomEntityWebsiteTable(), $condition);
        }

        if (!empty($insert) || !empty($delete)) {
            $entity->setIsChangedWebsites(true);
        }

        return $this;
    }
}
