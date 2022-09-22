<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity\AttributeSet;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Framework\Model\ResourceModel\Db\Context;

/**
 * Attribute set url resource model.
 */
class Url extends AbstractDb
{
    private FilterManager $filterManager;

    /**
     * Url constructor.
     *
     * @param Context $context Context.
     * @param FilterManager $filterManager Filter manager.
     * @param string|null $connectionName Connection name.
     */
    public function __construct(
        Context $context,
        FilterManager $filterManager,
        ?string $connectionName = null
    ) {
        parent::__construct($context, $connectionName);
        $this->filterManager = $filterManager;
    }

    /**
     * Find attribute set id from url key.
     *
     * @param string $urlKey Url key
     * @throws NotFoundException
     */
    public function checkIdentifier(string $urlKey): ?int
    {
        $urlKeyArray = explode('-', $urlKey);
        $select = $this->getConnection()->select();
        $select
            ->from($this->getTable('eav_attribute_set'), ['attribute_set_id', 'attribute_set_name'])
            // @codingStandardsIgnoreLine use like (MEQP1.SQL.SlowQuery.FoundSlowRawSql)
            ->where('attribute_set_name like ?', '%'.current($urlKeyArray).'%');
        $result = $this->getConnection()->fetchPairs($select);
        foreach ($result as $attributeSetId => $attributeSetName) {
            if ($this->filterManager->translitUrl($attributeSetName) == $urlKey) {
                return (int) $attributeSetId;
            }
            continue;
        }

        throw new NotFoundException(__('Not found attribute set'));
    }

    /**
     * Implementation of abstract construct
     */
    // @codingStandardsIgnoreLine use like (MEQP1.CodeAnalysis.EmptyBlock.DetectedFunction)
    protected function _construct(): void
    {
    }
}
