<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

/**
 * Custom entity attribute edit controller.
 */
class Edit extends \Smile\ScopedEav\Controller\Adminhtml\Attribute\Edit
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'Smile_CustomEntity::attributes_attributes';

    /**
     * Constructor.
     *
     * @param \Magento\Backend\App\Action\Context $context          Context.
     * @param \Smile\ScopedEav\Helper\Data        $entityHelper     Entity helper.
     * @param Builder                             $attributeBuilder Attribute builder.
     */
    // @codingStandardsIgnoreLine Override builder attribute (Generic.CodeAnalysis.UselessOverridingMethod.Found)
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Smile\ScopedEav\Helper\Data $entityHelper,
        Builder $attributeBuilder
    ) {
        parent::__construct($context, $entityHelper, $attributeBuilder);
    }
}
