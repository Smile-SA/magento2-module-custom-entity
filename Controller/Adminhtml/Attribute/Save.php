<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\ForwardFactory;
use Magento\Framework\Serialize\Serializer\FormData;
use Smile\ScopedEav\ViewModel\Data as DataViewModel;
use Zend\Validator\RegexFactory;

/**
 * Custom entity attribute save controller.
 */
class Save extends \Smile\ScopedEav\Controller\Adminhtml\Attribute\Save
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
        RegexFactory $regexFactory,
        FormData $formDataSerializer,
        ForwardFactory $resultForwardFactory
    ) {
        parent::__construct(
            $context,
            $dataViewModel,
            $attributeBuilder,
            $regexFactory,
            $formDataSerializer,
            $resultForwardFactory
        );
    }
}
