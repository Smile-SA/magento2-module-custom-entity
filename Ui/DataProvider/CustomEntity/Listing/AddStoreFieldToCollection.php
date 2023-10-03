<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Listing;

use Magento\Framework\Data\Collection;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;

/**
 * @api
 * @since 100.0.2
 */
class AddStoreFieldToCollection implements AddFilterToCollectionInterface
{
    protected StoreManagerInterface $storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function addFilter(Collection $collection, $field, $condition = null)
    {
        if (isset($condition['eq']) && $condition['eq']) {
            /** @var \Smile\CustomEntity\Model\ResourceModel\CustomEntity\Collection $collection  */
            /** @var Store $store */
            $store = $this->storeManager->getStore($condition['eq']);
            $collection->setStore($store);
        }
    }
}
