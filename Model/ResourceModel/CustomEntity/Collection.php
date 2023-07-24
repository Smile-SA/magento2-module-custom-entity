<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity;

use Magento\Catalog\Model\ResourceModel\Collection\AbstractCollection;
use Smile\CustomEntity\Model\CustomEntity;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity as CustomEntityResource;

/**
 * Custom entity collection model.
 */
class Collection extends AbstractCollection
{
    /**
     * Event prefix
     */
    protected string $_eventPrefix = 'custom_entity_collection';

    /**
     * Event object name
     */
    protected string $_eventObject = 'custom_entity_collection';

    /**
     * @inheritdoc
     */
    public function addIsActiveFilter(): self
    {
        $this->addAttributeToFilter('is_active', '1');
        $this->_eventManager->dispatch($this->_eventPrefix . '_add_is_active_filter', [$this->_eventObject => $this]);

        return $this;
    }

    /**
     * Init collection and determine table names
     */
    protected function _construct(): void
    {
        $this->_init(CustomEntity::class, CustomEntityResource::class);
    }
}
