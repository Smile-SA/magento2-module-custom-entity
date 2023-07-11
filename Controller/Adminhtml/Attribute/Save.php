<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Adminhtml\Attribute;

use Exception;
use InvalidArgumentException;
use Laminas\Validator\Regex;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\Serializer\FormData;
use Smile\CustomEntity\ViewModel\Attribute\Data as DataViewModel;
use Smile\ScopedEav\Api\Data\AttributeInterface;


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
        Builder $attributeBuilder,
        ForwardFactory $resultForwardFactory,
        protected DataViewModel $dataViewModel,
        protected ?FormData $formDataSerializer,
    ) {
        parent::__construct(
            $context,
            $dataViewModel,
            $attributeBuilder,
            $formDataSerializer,
            $resultForwardFactory
        );
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        try {
            $attribute = $this->getAttribute();
            $attribute = $this->addPostData($attribute);

            /** @var AbstractModel $attribute */
            $attribute->save();

            $response = $this->resultRedirectFactory->create();

            if ($this->getRequest()->getParam('back', false)) {
                $response->setPath(
                    "*/*/edit",
                    ['attribute_id' => $attribute->getId(), '_current' => true]
                );
            } else {
                $response->setPath('*/*/index');
            }
        } catch (Exception $e) {
            $response = $this->getRedirectError($e->getMessage());
        }

        return $response;
    }

    /**
     * Add request post data to the attribute.
     */
    private function addPostData(AttributeInterface $attribute): AttributeInterface
    {
        try {
            $optionData = $this->formDataSerializer
                ->unserialize($this->getRequest()->getParam('serialized_options', '[]'));
        } catch (InvalidArgumentException $e) {
            $message = (string) __("The attribute couldn't be saved due to an error."
                . " Verify your information and try again. If the error persists, please try again later.");
            $this->messageManager->addErrorMessage($message);
            // @phpstan-ignore-next-line
            return $this->returnResult('catalog/*/edit', ['_current' => true], ['error' => true]);
        }

        /** @var Http $request */
        $request = $this->getRequest();
        $data = $request->getPostValue();
        $data = array_replace_recursive(
            $data,
            $optionData
        );

        if (!$attribute->getAttributeId()) {
            $data['attribute_code']  = $this->getAttributeCode();
            $data['is_user_defined'] = true;
            $data['backend_type'] = $this->dataViewModel->getBackendTypeByInput($attribute, $data['frontend_input']);
            $data['source_model'] = $this->dataViewModel->getAttributeSourceModelByInputType($data['frontend_input']);
            $data['backend_model'] = $this->dataViewModel->getAttributeBackendModelByInputType($data['frontend_input']);
        }

        $frontendInput = $data['frontend_input'] ?? $attribute->getFrontendInput();
        $defaultValueField = $attribute->getDefaultValueByInput($frontendInput);
        if ($defaultValueField) {
            $data['default_value'] = $this->getRequest()->getParam($defaultValueField);
        }

        $attribute->addData($data);

        return $attribute;
    }

    /**
     * Generate attribute code from request params.
     *
     * @throws Exception
     */
    private function getAttributeCode(): string
    {
        $attributeCode = $this->getRequest()->getParam('attribute_code');

        if (!$attributeCode) {
            $attributeCode = $this->generateCode($this->getRequest()->getParam('frontend_label')[0]);
        }

        if (trim($attributeCode) == '') {
            /** @var Regex $validatorAttrCode */
            $validatorAttrCode = new Regex('/^[a-z][a-z_0-9]{0,30}$/');

            if (!$validatorAttrCode->isValid($attributeCode)) {
                $errorMessageParts = [
                    __('Attribute code "%1" is invalid.', $attributeCode),
                    __('Please use only letters (a-z), numbers (0-9) or underscore(_) in this field.'),
                    __('First character should be a letter.'),
                ];
                throw new LocalizedException(__(implode(' ', $errorMessageParts), $attributeCode));
            }
        }

        return $attributeCode;
    }
}
