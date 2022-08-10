<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api\Data;

/**
 * Custom entity attribute search result interface.
 *
 * @api
 */
interface CustomEntityAttributeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface[] $items Items.
     *
     * @return $this
     */
    public function setItems(array $items);
}
