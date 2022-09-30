<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Html;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Pager block to works with getList method of repository class.
 */
class Pager extends \Magento\Theme\Block\Html\Pager
{
    /**
     * @inheritdoc
     */
    protected $_template = 'Smile_CustomEntity::html/pager.phtml';

    private SearchResultsInterface $searchResult;

    /**
     * Add current page and page size condition into search criteria builder.
     *
     * @param SearchCriteriaBuilder $searchCriteriaBuilder Search criteria builder.
     */
    public function addCriteria(SearchCriteriaBuilder $searchCriteriaBuilder): ?SearchCriteriaBuilder
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
     * @return $this
     */
    public function setSearchResult(SearchResultsInterface $searchResult): self
    {
        $this->searchResult = $searchResult;

        return $this;
    }

    /**
     * Return first page number.
     */
    public function getFirstNum(): ?int
    {
        return $this->getLimit() * ($this->getCurrentPage() - 1) + 1;
    }

    /**
     * Return last page number.
     */
    public function getLastNum(): ?int
    {
        return $this->getLimit() * ($this->getCurrentPage() - 1) + count($this->searchResult->getItems());
    }

    /**
     * Retrieve total number of items.
     */
    public function getTotalNum(): ?int
    {
        return$this->searchResult->getTotalCount();
    }

    /**
     * Check if current page is a first page in collection
     */
    public function isFirstPage(): bool
    {
        return $this->getCurrentPage() == 1;
    }

    /**
     * Retrieve number of last page
     */
    public function getLastPageNum(): int
    {
        return (int) ceil($this->searchResult->getTotalCount() / $this->getLimit());
    }

    /**
     * Check if current page is a last page in collection
     */
    public function isLastPage(): bool
    {
        return $this->getCurrentPage() >= $this->getLastPageNum();
    }

    /**
     * Return pages range.
     *
     * @return array|null
     */
    public function getPages(): ?array
    {
        [$start, $finish] = $this->getPagesInterval();

        return range($start, $finish);
    }

    /**
     * Return previous page url.
     */
    public function getPreviousPageUrl(): string
    {
        $page = (string) ($this->getCurrentPage() - 1);
        return $this->getPageUrl($page);
    }

    /**
     * Return next page url.
     */
    public function getNextPageUrl(): string
    {
        $page = (string) ($this->getCurrentPage() + 1);
        return $this->getPageUrl($page);
    }

    /**
     * Retrieve last page URL.
     */
    public function getLastPageUrl(): string
    {
        $lastNumPage = (string) $this->getLastPageNum();
        return $this->getPageUrl($lastNumPage);
    }

    /**
     * Initialize frame data, such as frame start, frame start etc.
     *
     * @return $this
     */
    protected function _initFrame(): self
    {
        if (!$this->isFrameInitialized()) {
            [$start, $end] = $this->getPagesInterval();
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
        if (
            $this->getCurrentPage() >= $half &&
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
