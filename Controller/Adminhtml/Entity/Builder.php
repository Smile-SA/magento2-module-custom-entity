<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Entity;

use Magento\Cms\Model\Wysiwyg\Config;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterfaceFactory;
use Smile\ScopedEav\Api\Data\EntityInterface;
use Smile\ScopedEav\Controller\Adminhtml\Entity\BuilderInterface;

/**
 * Admin controller custom entity builder.
 */
class Builder implements BuilderInterface
{
    private CustomEntityInterfaceFactory $customEntityFactory;

    private StoreManagerInterface $storeManager;

    private Config $wysiwygConfig;

    private Registry $registry;

    /**
     * Constructor.
     *
     * @param CustomEntityInterfaceFactory $customEntityFactory Custom entity factory.
     * @param StoreManagerInterface $storeManager Store manager.
     * @param Registry $registry Registry.
     * @param Config $wysiwygConfig Wysiwyg config.
     */
    public function __construct(
        CustomEntityInterfaceFactory $customEntityFactory,
        StoreManagerInterface $storeManager,
        Registry $registry,
        Config $wysiwygConfig
    ) {
        $this->customEntityFactory = $customEntityFactory;
        $this->storeManager        = $storeManager;
        $this->registry            = $registry;
        $this->wysiwygConfig       = $wysiwygConfig;
    }

    /**
     * Build
     *
     * @return CustomEntityInterface|EntityInterface
     * @throws NoSuchEntityException
     */
    // @codingStandardsIgnoreLine Move class into Model folder (MEQP2.Classes.PublicNonInterfaceMethods.PublicMethodFound)
    public function build(RequestInterface $request): ?EntityInterface
    {
        $entityId = (int) $request->getParam('id');
        $entity = $this->customEntityFactory->create();
        $store = $this->storeManager->getStore((int) $request->getParam('store', 0));
        $entity->setStoreId($store->getId());

        /** @var DataObject $entity */
        $entity->setData('_edit_mode', true);

        if ($entityId) {
            // @phpstan-ignore-next-line
            $entity->load($entityId);
        }

        $setId = (int) $request->getParam('set');

        if ($setId) {
            $entity->setAttributeSetId($setId);
        }

        $entity->setPrevAttributeSetId((int) $request->getParam('prev_set_id', 0));

        $this->registry->register('entity', $entity);
        $this->registry->register('current_entity', $entity);
        $this->registry->register('current_store', $store);
        $this->wysiwygConfig->setStoreId($request->getParam('store'));

        /** @var EntityInterface $entity */
        return $entity;
    }
}
