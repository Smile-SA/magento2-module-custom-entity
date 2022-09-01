<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action\Context;
use Smile\ScopedEav\Helper\Data;
use Zend\Validator\RegexFactory;

/**
 * Custom entity attribute save controller.
 */
class Save extends \Smile\ScopedEav\Controller\Adminhtml\Attribute\Save
{
    /**
     * @var string
     */
    const ADMIN_RESOURCE = 'Smile_CustomEntity::attributes_attributes';

    /**
     * Constructor.
     *
     * @param Context $context Context.
     * @param Data $entityHelper Entity helper.
     * @param Builder $attributeBuilder Attribute builder.
     */
    // @codingStandardsIgnoreLine Override builder attribute (Generic.CodeAnalysis.UselessOverridingMethod.Found)
    public function __construct(
        Context $context,
        Data $entityHelper,
        Builder $attributeBuilder,
        RegexFactory $regexFactory
    ) {
        parent::__construct($context, $entityHelper, $attributeBuilder, $regexFactory);
    }
}
