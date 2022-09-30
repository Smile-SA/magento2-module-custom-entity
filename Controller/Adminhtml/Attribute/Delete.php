<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\ForwardFactory;
use Smile\ScopedEav\ViewModel\Data as DataViewModel;

/**
 * Custom entity attribute delete controller.
 */
class Delete extends \Smile\ScopedEav\Controller\Adminhtml\Attribute\Delete
{
    public const ADMIN_RESOURCE = 'Smile_CustomEntity::attributes_attributes';

    /**
     * Constructor.
     *
     * @param Context $context Context.
     * @param DataViewModel $dataViewModel Scoped EAV data view model.
     * @param Builder $attributeBuilder Attribute builder.
     */
    // @codingStandardsIgnoreLine Override builder attribute (Generic.CodeAnalysis.UselessOverridingMethod.Found)
    public function __construct(
        Context $context,
        DataViewModel $dataViewModel,
        Builder $attributeBuilder,
        ForwardFactory $resultForwardFactory
    ) {
        parent::__construct(
            $context,
            $dataViewModel,
            $attributeBuilder,
            $resultForwardFactory
        );
    }
}
