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

namespace Smile\CustomEntity\Controller\Adminhtml\Entity;

/**
 * Reload custom entity admin controller.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Reload extends \Smile\ScopedEav\Controller\Adminhtml\Entity\Reload
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'Smile_CustomEntity::entity';
}
