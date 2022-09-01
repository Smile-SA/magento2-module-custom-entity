<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\Grid;

use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\Search\SearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\Collection as AttributeCollection;

/**
 * Custom entity attribute collection (grid).
 */
class Collection extends AttributeCollection implements SearchResultInterface
{

    /**
     * @var AggregationInterface
     */
    private $aggregations;

    /**
     * {@inheritDoc}
     */
    public function getAggregations()
    {
        return $this->aggregations;
    }

    /**
     * {@inheritDoc}
     */
    public function getSearchCriteria()
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalCount()
    {
        return $this->getSize();
    }

    /**
     * {@inheritDoc}
     * Not implemented since useless.
     */
    public function setSearchCriteria(SearchCriteriaInterface $searchCriteria = null): self
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setAggregations($aggregations)
    {
        $this->aggregations = $aggregations;
    }

    /**
     * {@inheritDoc}
     * Not implemented since useless.
     */
    public function setTotalCount($totalCount): self
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     * Not implemented since useless.
     */
    public function setItems(array $items = null): self
    {
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function _construct()
    {
        $this->_init(
            \Magento\Framework\View\Element\UiComponent\DataProvider\Document::class,
            \Magento\Eav\Model\ResourceModel\Entity\Attribute::class
        );
    }
}
