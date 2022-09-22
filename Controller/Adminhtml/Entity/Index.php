<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Entity;

use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Smile\ScopedEav\Controller\Adminhtml\AbstractEntity;

/**
 * Custom entity admin list controller.
 */
class Index extends AbstractEntity implements HttpGetActionInterface
{
    public const ADMIN_RESOURCE = 'Smile_CustomEntity::entity';

    /**
     * Execute
     */
    public function execute(): Page
    {
        return $this->createActionPage(__('Custom Entities'));
    }
}
