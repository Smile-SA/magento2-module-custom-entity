<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Html;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Pager block to works with getList method of repository class.
 */
class Pager extends \Magento\Theme\Block\Html\Pager
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'Smile_CustomEntity::html/pager.phtml';

    /**
     * @var SearchResultsInterface
     */
    private $searchResult;

    /**
     * Add current page and page size condition into search criteria builder.
     *
     * @param \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder Search criteria builder.
     *
     * @return \Magento\Framework\Api\SearchCriteriaBuilder
     */
    public function addCriteria(\Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder)
    {
        $searchCriteriaBuilder->setCurrentPage($this->getCurrentPage());
        if ((int) $this->getLimit()) {
            $searchCriteriaBuilder->setPageSize($this->getLimit());
        }

        return $searchCriteriaBuilder;
    }

    /**
     * Set search result.
     *
     * @param SearchResultsInterface $searchResult Search result.
     *
     * @return $this
     */
    public function setSearchResult(SearchResultsInterface $searchResult)
    {
        $this->searchResult = $searchResult;

        return $this;
    }

    /**
     * Return first page number.
     *
     * @return int
     */
    public function getFirstNum()
    {
        return $this->getLimit() * ($this->getCurrentPage() - 1) + 1;
    }

    /**
     * Return last page number.
     *
     * @return int
     */
    public function getLastNum()
    {
        return $this->getLimit() * ($this->getCurrentPage() - 1) + count($this->searchResult->getItems());
    }

    /**
     * Retrieve total number of items.
     *
     * @return int
     */
    public function getTotalNum()
    {
        return$this->searchResult->getTotalCount();
    }

    /**
     * Check if current page is a first page in collection
     *
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->getCurrentPage() == 1;
    }

    /**
     * Retrieve number of last page
     *
     * @return int
     */
    public function getLastPageNum()
    {
        return ceil($this->searchResult->getTotalCount() / $this->getLimit());
    }

    /**
     * Check if current page is a last page in collection
     *
     * @return bool
     */
    public function isLastPage()
    {
        return $this->getCurrentPage() >= $this->getLastPageNum();
    }

    /**
     * Return pages range.
     *
     * @return array
     */
    public function getPages()
    {
        list($start, $finish) = $this->getPagesInterval();

        return range($start, $finish);
    }

    /**
     * Return previous page url.
     *
     * @return string
     */
    public function getPreviousPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPage() - 1);
    }

    /**
     * Return next page url.
     *
     * @return string
     */
    public function getNextPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPage() + 1);
    }

    /**
     * Retrieve last page URL.
     *
     * @return string
     */
    public function getLastPageUrl()
    {
        return $this->getPageUrl($this->getLastPageNum());
    }

    /**
     * Initialize frame data, such as frame start, frame start etc.
     *
     * @return $this
     */
    protected function _initFrame()
    {
        if (!$this->isFrameInitialized()) {
            list($start, $end) = $this->getPagesInterval();
            $this->_frameStart = $start;
            $this->_frameEnd = $end;
            $this->_setFrameInitialized(true);
        }

        return $this;
    }

    /**
     * Return start and end pages number.
     *
     * @return array
     */
    private function getPagesInterval(): array
    {
        if ($this->getLastPageNum() <= $this->_displayPages) {
            return [1, $this->getLastPageNum()];
        }

        $half = ceil($this->_displayPages / 2);
        $start = 1;
        $finish = $this->_displayPages;
        if ($this->getCurrentPage() >= $half &&
            $this->getCurrentPage() <= $this->getLastPageNum() - $half
        ) {
            $start = $this->getCurrentPage() - $half + 1;
            $finish = $start + $this->_displayPages - 1;
        } elseif ($this->getCurrentPage() > $this->getLastPageNum() - $half) {
            $finish = $this->getLastPageNum();
            $start = $finish - $this->_displayPages + 1;
        }

        return [$start, $finish];
    }
}
