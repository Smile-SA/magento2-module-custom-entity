<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Smile Custom Entity module to newer
 * versions in the future.
 *
 *
 * @category  Smile
 * @package   Smile\CustomEntity
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\CustomEntity\Block\Adminhtml;

use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity listing main container.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Entity extends \Smile\ScopedEav\Block\Adminhtml\AbstractEntity
{
    /**
     * {@inheritDoc}
     */
    protected function getEntityTypeCode()
    {
        return CustomEntityInterface::ENTITY;
    }
}
