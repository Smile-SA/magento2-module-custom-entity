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

namespace Smile\CustomEntity\Controller\Adminhtml\Entity;

/**
 * Admin controller custom entity builder.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Builder implements \Smile\ScopedEav\Controller\Adminhtml\Entity\BuilderInterface
{
    /**
     * @var \Smile\CustomEntity\Api\Data\CustomEntityInterfaceFactory
     */
    private $customEntityFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    private $wysiwygConfig;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityInterfaceFactory $customEntityFactory Custom entity factory.
     * @param \Magento\Store\Model\StoreManagerInterface                            $storeManager        Store manager.
     * @param \Magento\Framework\Registry                                           $registry            Registry.
     * @param \Magento\Cms\Model\Wysiwyg\Config                                     $wysiwygConfig       Wysiwyg config.
     */
    public function __construct(
        \Smile\CustomEntity\Api\Data\CustomEntityInterfaceFactory $customEntityFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Magento\Cms\Model\Wysiwyg\Config $wysiwygConfig
    ) {
        $this->customEntityFactory = $customEntityFactory;
        $this->storeManager        = $storeManager;
        $this->registry            = $registry;
        $this->wysiwygConfig       = $wysiwygConfig;
    }

    /**
     * {@inheritDoc}
     */
    public function build(\Magento\Framework\App\RequestInterface $request)
    {
        $entityId = (int) $request->getParam('id');
        $entity   = $this->customEntityFactory->create();
        $store    = $this->storeManager->getStore((int) $request->getParam('store', 0));

        $entity->setStoreId($store->getId());
        $entity->setData('_edit_mode', true);

        if ($entityId) {
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

        return $entity;
    }
}
