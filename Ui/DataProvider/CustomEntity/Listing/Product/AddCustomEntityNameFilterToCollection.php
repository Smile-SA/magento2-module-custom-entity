<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Listing;

use Magento\Framework\Data\Collection;
use Magento\Ui\DataProvider\AddFieldToCollectionInterface;

/**
 * Add Custom entity field to collection.
 */
class AddCustomEntityFieldToCollection implements \Magento\Ui\DataProvider\AddFilterToCollectionInterface
{
    /**
     * @inheritDoc
     */
    public function addFilter(Collection $collection, $field, $condition = null)
    {
        if (isset($condition['eq'])) {
            $collection->addFieldToFilter($field, $condition);
        }
    }
}
