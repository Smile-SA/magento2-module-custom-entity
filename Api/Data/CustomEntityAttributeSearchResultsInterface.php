<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CustomEntityAttributeSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return CustomEntityAttributeInterface[]|null
     */
    public function getItems(): ?array;

    /**
     * Set attributes list.
     *
     * @param CustomEntityAttributeInterface[] $items Items.
     * @return $this
     */
    public function setItems(array $items): self;
}
