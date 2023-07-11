<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Adminhtml\Form\Element;

use Magento\Backend\Block\Widget\Form\Element\Dependence as MagentoDependence;

/**
 * Form element dependencies mapper.
 */
class Dependence extends MagentoDependence
{
    /**
     * @inheritDoc
     *
     * Rewriting the base method to be able to add dependencies from multiple values from field.
     */
    public function addFieldDependence($fieldName, $fieldNameFrom, $refField)
    {
        if (!is_object($refField)) {
            $refField = $this->_fieldFactory->create(
                ['fieldData' => ['value' => (string) $refField, 'separator' => ','], 'fieldPrefix' => '']
            );
        }
        $this->_depends[$fieldName][$fieldNameFrom] = $refField;

        return $this;
    }
}
