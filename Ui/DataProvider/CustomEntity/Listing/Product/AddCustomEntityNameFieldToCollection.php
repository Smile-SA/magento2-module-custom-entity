<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Listing;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFieldToCollectionInterface;

/**
 * Add Custom entity field to collection.
 */
class AddCustomEntityNameFieldToCollection implements AddFieldToCollectionInterface
{
    /**
     * @inheritDoc
     */
    public function addField(Collection $collection, $field, $alias = null)
    {
        $collection->joinField('smile_custom_entity_name', 'cataloginventory_stock_item', 'manage_stock', 'product_id=entity_id', null, 'left');
    }
}
