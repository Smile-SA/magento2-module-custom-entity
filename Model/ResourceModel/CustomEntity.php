<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel;

use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\Entity\Context;
use Magento\Eav\Model\Entity\TypeFactory;
use Magento\Framework\DataObject;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Store\Model\StoreManagerInterface;
use Smile\ScopedEav\Model\Entity\Attribute\DefaultAttributes;
use Smile\ScopedEav\Model\ResourceModel\AbstractResource;

/**
 * Custom entity resource model.
 */
class CustomEntity extends AbstractResource
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
     * @param Context $context Context.
     * @param EntityManager $entityManager Entity manager.
     * @param TypeFactory  $typeFactory Type factory.
     * @param SetFactory $setFactory Attribute set factory.
     * @param DefaultAttributes $defaultAttributes Default attributes.
     * @param StoreManagerInterface $storeManager Store manager.
     * @param array $data Data.
     */
    public function __construct(
        Context $context,
        EntityManager $entityManager,
        TypeFactory $typeFactory,
        SetFactory $setFactory,
        DefaultAttributes $defaultAttributes,
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
     * @return string|null
     */
    public function getCustomEntityWebsiteTable(): ?string
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
     * @return array|null
     */
    public function getWebsiteIds($entity): ?array
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
    protected function _afterSave(DataObject $entity)
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
    protected function saveWebsiteIds($entity): self
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
