<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Registry;
use Smile\CustomEntity\Api\Data\CustomEntityAttributeInterface;

/**
 * Custom entity attribute storefront properties form.
 */
class Front extends Generic
{
    private Yesno $yesNo;

    /**
     * Constructor.
     *
     * @param Context $context Context.
     * @param Registry $registry Registry.
     * @param FormFactory $formFactory Form factory.
     * @param Yesno $yesNo Yes/No source model
     * @param array $data Additional data.
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Yesno $yesNo,
        array $data = []
    ) {
        parent::__construct($context, $registry, $formFactory, $data);
        $this->yesNo = $yesNo;
    }

    /**
     * @inheritdoc
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _prepareForm()
    {
        /** @var DataObject $attributeObject */
        $attributeObject = $this->getAttributeObject();

        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create(
            ['data' => ['id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post']]
        );

        $fieldset = $form->addFieldset('front_fieldset', ['legend' => __('Storefront Properties')]);

        $fieldset->addField('is_wysiwyg_enabled', 'select', [
            'name' => 'is_wysiwyg_enabled',
            'label' => __('Enable WYSIWYG'),
            'title' => __('Enable WYSIWYG'),
            'values' => $this->yesNo->toOptionArray(),
        ]);

        $fieldset->addField('is_html_allowed_on_front', 'select', [
            'name' => 'is_html_allowed_on_front',
            'label' => __('Allow HTML Tags on Storefront'),
            'title' => __('Allow HTML Tags on Storefront'),
            'values' => $this->yesNo->toOptionArray(),
        ]);

        $this->setForm($form);
        $form->setValues($attributeObject->getData());

        return parent::_prepareForm();
    }

    /**
     * Retrieve attribute object from registry
     */
    private function getAttributeObject(): ?CustomEntityAttributeInterface
    {
        return $this->_coreRegistry->registry('entity_attribute');
    }
}
