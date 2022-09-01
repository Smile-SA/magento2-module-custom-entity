<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Form;

use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\CollectionFactory;
use Smile\ScopedEav\Ui\DataProvider\Entity\Form\EntityDataProvider;

/**
 * Custom entity edit form dataprovider.
 */
class CustomEntityDataProvider extends EntityDataProvider
{
    /**
     * Constructor.
     *
     * @param string $name  Source name.
     * @param string $primaryFieldName Primary field name.
     * @param string $requestFieldName  Request field name.
     * @param PoolInterface $pool Form modifier pool.
     * @param CollectionFactory $collectionFactory Collection factory.
     * @param array $meta Original meta.
     * @param array $data Original data.
     */
    // @codingStandardsIgnoreLine Use the factory (MEQP2.Classes.ConstructorOperations.CustomOperationsFound)
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        PoolInterface $pool,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $pool, $meta, $data);

        $this->collection = $collectionFactory->create();
    }
}
