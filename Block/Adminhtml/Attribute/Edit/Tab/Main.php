<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Adminhtml\Attribute\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Form\Element\Dependence;
use Magento\Config\Model\Config\Source\YesnoFactory;
use Magento\Eav\Block\Adminhtml\Attribute\PropertyLocker;
use Magento\Eav\Helper\Data;
use Magento\Eav\Model\Adminhtml\System\Config\Source\InputtypeFactory;
use Magento\Framework\Data\FormFactory;
use Magento\Framework\Registry;
use Smile\CustomEntity\Model\CustomEntity\AttributeSet\Options;

/**
 * Custom entity attribute edit main form.
 *
 * @SuppressWarnings(PHPMD.ExcessiveParameterList)
 */
class Main extends \Smile\ScopedEav\Block\Adminhtml\Attribute\Edit\Tab\Main
{
    protected Options $attributeSetOptions;

    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Data $eavData,
        YesnoFactory $yesnoFactory,
        InputtypeFactory $inputTypeFactory,
        PropertyLocker $propertyLocker,
        Options $attributeSetOptions,
        array $disableScopeChangeList = [],
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $formFactory,
            $eavData,
            $yesnoFactory,
            $inputTypeFactory,
            $propertyLocker,
            $disableScopeChangeList,
            $data
        );
        $this->attributeSetOptions = $attributeSetOptions;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareForm(): self
    {
        parent::_prepareForm();

        $form     = $this->getForm();
        $fieldset = $form->getElement('base_fieldset');

        // Add input to select custom entity type.
        $fieldset->addField(
            'custom_entity_attribute_set_id',
            'select',
            [
                'name'  => 'custom_entity_attribute_set_id',
                'label'  => __('Custom entity type'),
                'title'  => __('Custom entity type'),
                'component' => 'Magento_Catalog/js/components/visible-on-option/fieldset',
                'values' => array_merge(
                    [['value' => null, 'label' => __('Select...')]],
                    $this->attributeSetOptions->toOptionArray()
                ),
                'visible' => 'false',
            ],
            'frontend_input'
        );

        /** @var Dependence $block */
        $block = $this->getLayout()->createBlock(Dependence::class);

        $block->addFieldMap(
            "frontend_input",
            'frontend_input_type'
        )->addFieldMap(
            "custom_entity_attribute_set_id",
            'custom_entity_attribute_set_id'
        )->addFieldDependence(
            'custom_entity_attribute_set_id',
            'frontend_input_type',
            'smile_custom_entity_select'
        );

        //Added dependency between 'Custom entity type' and 'Input Type'.
        $this->setChild('form_after', $block);

        // Disable 'Custom entity type' input if the attribute is already created.
        if ($this->getAttributeObject() && $this->getAttributeObject()->getAttributeId()) {
            $form->getElement('custom_entity_attribute_set_id')->setDisabled(1);
        }

        return $this;
    }
}
