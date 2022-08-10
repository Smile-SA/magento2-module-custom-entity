<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Entity;

/**
 * Delete custom entity admin controller.
 */
class Delete extends \Smile\ScopedEav\Controller\Adminhtml\Entity\Delete
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'Smile_CustomEntity::entity';
}
