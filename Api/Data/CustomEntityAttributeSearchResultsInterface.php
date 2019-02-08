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

namespace Smile\CustomEntity\Api\Data;

/**
 * Custom entity attribute search result interface.
 *
 * @api
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
interface CustomEntityAttributeSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get attributes list.
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface[]
     */
    public function getItems();

    /**
     * Set attributes list.
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface[] $items Items.
     *
     * @return $this
     */
    public function setItems(array $items);
}
