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

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Listing;

/**
 * Custom entity listing form dataprovider.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class CustomEntityDataProvider extends \Smile\ScopedEav\Ui\DataProvider\Entity\Listing\EntityDataProvider
{
/**
     * Constructor.
     *
     * @param string                                                                             $name                Name.
     * @param string                                                                             $primaryFieldName    Primary field name.
     * @param string                                                                             $requestFieldName    Request field name.
     * @param \Smile\CustomEntity\Model\ResourceModel\CustomEntity\CollectionFactory $collectionFactory   Collection factory.
     * @param \Magento\Ui\DataProvider\AddFieldToCollectionInterface[]                           $addFieldStrategies  Field add stategies.
     * @param \Magento\Ui\DataProvider\AddFilterToCollectionInterface[]                          $addFilterStrategies Filter strategies.
     * @param array                                                                              $meta                Meta.
     * @param array                                                                              $data                Data.
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Smile\CustomEntity\Model\ResourceModel\CustomEntity\CollectionFactory $collectionFactory,
        array $addFieldStrategies = [],
        array $addFilterStrategies = [],
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $addFieldStrategies, $addFilterStrategies, $meta, $data);

        $this->collection = $collectionFactory->create();
    }
}
