<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Form\Modifier;

use Magento\Ui\Component\Form\Field;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Model\CustomEntity\AttributeSet\Options;
use Smile\ScopedEav\Model\Locator\LocatorInterface;
use Smile\ScopedEav\Ui\DataProvider\Entity\Form\Modifier\AbstractModifier;

/**
 * Custom entity attribute set edit field management.
 */
class AttributeSet extends AbstractModifier
{
    private const ATTRIBUTE_SET_FIELD_ORDER = 30;

    private Options $attributeSetOptions;

    private LocatorInterface $locator;

    /**
     * Constructor.
     *
     * @param LocatorInterface $locator Entity locator.
     * @param Options $attributeSetOptions Attribute set source model.
     */
    public function __construct(
        LocatorInterface $locator,
        Options $attributeSetOptions
    ) {
        $this->attributeSetOptions = $attributeSetOptions;
        $this->locator = $locator;
    }

    /**
     * @inheritdoc
     */
    public function modifyMeta(array $meta)
    {
        $name = $this->getFirstPanelCode($meta);
        if ($name && $this->locator->getEntity()->getId() == null) {
            $meta[$name]['children']['attribute_set_id']['arguments']['data']['config'] = [
                'component' => 'Magento_Catalog/js/components/attribute-set-select',
                'disableLabel' => true,
                'filterOptions' => true,
                'elementTmpl' => 'ui/grid/filters/elements/ui-select',
                'formElement' => 'select',
                'componentType' => Field::NAME,
                'options' => $this->attributeSetOptions->toOptionArray(),
                'visible' => 1,
                'required' => 1,
                'label' => __('Attribute Set'),
                'source' => $name,
                'dataScope' => 'attribute_set_id',
                'multiple' => false,
                'sortOrder' => $this->getNextAttributeSortOrder(
                    $meta,
                    [CustomEntityInterface::IS_ACTIVE],
                    self::ATTRIBUTE_SET_FIELD_ORDER
                ),
            ];
        }

        return $meta;
    }

    /**
     * @inheritdoc
     */
    public function modifyData(array $data)
    {
        $entity = $this->locator->getEntity();
        $data[$entity->getId()][self::DATA_SOURCE_DEFAULT]['attribute_set_id'] = $entity->getAttributeSetId();

        return $data;
    }
}
