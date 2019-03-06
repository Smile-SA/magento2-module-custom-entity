<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\CustomEntity
 * @author    Maxime Leclercq <maxime.leclercq@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\CustomEntity\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Custom entity search results interface.
 *
 * @api
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Maxime Leclercq <maxime.leclercq@smile.fr>
 */
interface CustomEntitySearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get custom entities list.
     *
     * @return CustomEntityInterface[]
     */
    public function getItems();

    /**
     * Set custom entities list.
     *
     * @param CustomEntityInterface[] $items Items.
     *
     * @return $this
     */
    public function setItems(array $items);
}
