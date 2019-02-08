<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\CustomEntity
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Form;

use Magento\Ui\DataProvider\Modifier\PoolInterface;

/**
 * Custom entity edit form dataprovider.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class CustomEntityDataProvider extends \Smile\ScopedEav\Ui\DataProvider\Entity\Form\EntityDataProvider
{
    /**
     * Constructor.
     *
     * @param string                                                                             $name              Source name.
     * @param string                                                                             $primaryFieldName  Primary field name.
     * @param string                                                                             $requestFieldName  Request field name.
     * @param \Magento\Ui\DataProvider\Modifier\PoolInterface                                    $pool              Form modifier pool.
     * @param \Smile\CustomEntity\Model\ResourceModel\CustomEntity\CollectionFactory $collectionFactory Collection factory.
     * @param array                                                                              $meta              Original meta.
     * @param array                                                                              $data              Original data.
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Ui\DataProvider\Modifier\PoolInterface $pool,
        \Smile\CustomEntity\Model\ResourceModel\CustomEntity\CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $pool, $meta, $data);

        $this->collection = $collectionFactory->create();
    }
}
