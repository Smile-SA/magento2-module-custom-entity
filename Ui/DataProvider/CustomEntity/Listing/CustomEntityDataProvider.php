<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Listing;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Magento\Ui\DataProvider\AddFieldToCollectionInterface;
use Magento\Ui\DataProvider\AddFilterToCollectionInterface;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\CollectionFactory;
use Smile\ScopedEav\Ui\DataProvider\Entity\Listing\EntityDataProvider;

/**
 * Custom entity listing form dataprovider.
 */
class CustomEntityDataProvider extends EntityDataProvider
{
    /**
     * Constructor.
     *
     * @param string $name Name.
     * @param string $primaryFieldName Primary field name.
     * @param string $requestFieldName Request field name.
     * @param CollectionFactory $collectionFactory  Collection factory.
     * @param AddFieldToCollectionInterface[] $addFieldStrategies Field add stategies.
     * @param AddFilterToCollectionInterface[] $addFilterStrategies Filter strategies.
     * @param array $meta Meta.
     * @param array $data Data.
     */
    // @codingStandardsIgnoreLine Use the factory (MEQP2.Classes.ConstructorOperations.CustomOperationsFound)
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $addFieldStrategies,
            $addFilterStrategies,
            $meta,
            $data
        );

        /** @var AbstractCollection $collectionFactory */
        $collectionFactory = $collectionFactory->create();
        $this->collection = $collectionFactory;
    }
}
