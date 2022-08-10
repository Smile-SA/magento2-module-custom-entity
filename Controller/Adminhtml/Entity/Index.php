<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Entity;

/**
 * Custom entity admin list controller.
 */
class Index extends \Smile\ScopedEav\Controller\Adminhtml\AbstractEntity
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'Smile_CustomEntity::entity';

    /**
     *
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        return $this->createActionPage(__('Custom Entities'));
    }
}
