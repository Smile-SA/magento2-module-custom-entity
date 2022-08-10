<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity;

/**
 * Custom entity collection model.
 */
class Collection extends \Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection
{
    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'custom_entity_collection';

    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject = 'custom_entity_collection';

    /**
     * {@inheritDoc}
     */
    public function addIsActiveFilter()
    {
        $this->addAttributeToFilter('is_active', 1);
        $this->_eventManager->dispatch($this->_eventPrefix . '_add_is_active_filter', [$this->_eventObject => $this]);

        return $this;
    }

    /**
     * Init collection and determine table names
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Smile\CustomEntity\Model\CustomEntity',
            'Smile\CustomEntity\Model\ResourceModel\CustomEntity'
        );
    }
}
