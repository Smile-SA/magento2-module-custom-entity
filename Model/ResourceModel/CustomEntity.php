<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel;

use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\Entity\Context;
use Magento\Eav\Model\Entity\TypeFactory;
use Magento\Framework\DataObject;
use Magento\Framework\EntityManager\EntityManager;
use Magento\Store\Model\StoreManagerInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Model\CustomEntity as CustomEntityModel;
use Smile\ScopedEav\Model\Entity\Attribute\DefaultAttributes;
use Smile\ScopedEav\Model\ResourceModel\AbstractResource;

/**
 * Custom entity resource model.
 */
class CustomEntity extends AbstractResource
{
    protected string $customEntityWebsiteTable;

    private StoreManagerInterface $storeManager;

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
        $this->customEntityWebsiteTable = CustomEntityInterface::ENTITY;
    }

    /**
     * @inheritdoc
     */
    public function getEntityType()
    {
        $this->setType(CustomEntityInterface::ENTITY);
        return parent::getEntityType();
    }

    /**
     * Custom entity website table name getter.
     */
    public function getCustomEntityWebsiteTable(): ?string
    {
        if (!empty($this->customEntityWebsiteTable)) {
            $this->customEntityWebsiteTable = $this->getTable('smile_custom_entity_website');
        }

        return $this->customEntityWebsiteTable;
    }

    /**
     * Retrieve custom entity website identifiers
     *
     * @param CustomEntityModel|int $entity Custom entity.
     * @return array|null
     */
    public function getWebsiteIds($entity): ?array
    {
        $connection = $this->getConnection();

        $entityId = $entity;

        if ($entity instanceof CustomEntityModel) {
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
     * @inheritdoc
     */
    protected function _afterSave(DataObject $entity)
    {
        /** @var CustomEntity $this */
        /** @var CustomEntityModel $entity */
        $this->saveWebsiteIds($entity);
        return parent::_afterSave($entity);
    }

    /**
     * Save entity website relations
     *
     * @param CustomEntityModel $entity Entity.
     * @return $this
     */
    protected function saveWebsiteIds(CustomEntityModel $entity): self
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
