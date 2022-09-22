<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface CustomEntitySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get custom entities list.
     *
     * @return CustomEntityInterface[]|null
     */
    public function getItems(): ?array;

    /**
     * Set custom entities list.
     *
     * @param CustomEntityInterface[] $items Items.
     * @return $this
     */
    public function setItems(array $items): self;
}
