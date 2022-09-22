<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\Grid;

use Magento\Eav\Model\ResourceModel\Entity\Attribute;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\View\Element\UiComponent\DataProvider\Document;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\Collection as AttributeCollection;

/**
 * Custom entity attribute collection (grid).
 */
class Collection extends AttributeCollection implements SearchResultInterface
{
    /**
     * Aggregation interface
     */
    private AggregationInterface $aggregations;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            Document::class,
            Attribute::class
        );
    }

    /**
     * @inheritDoc
     */
    public function getAggregations(): AggregationInterface
    {
        return $this->aggregations;
    }

    /**
     * @inheritDoc
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * @inheritDoc
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria)
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setAggregations($aggregations): self
    {
        $this->aggregations = $aggregations;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setTotalCount($totalCount): self
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setItems(?array $items = null): self
    {
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSearchCriteria(): ?SearchCriteriaInterface
    {
        return null;
    }
}
