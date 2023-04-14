<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Ui\DataProvider\CustomEntity\Form\Modifier;

use Smile\ScopedEav\Model\Locator\LocatorInterface;
use Smile\ScopedEav\Ui\DataProvider\Entity\Form\Modifier\AbstractModifier;
use Smile\ScopedEav\Ui\DataProvider\Entity\Form\Modifier\Helper\Eav as EavHelper;

class CustomEntityAttribute extends AbstractModifier
{
    /**
     * Constructor.
     */
    public function __construct(
        protected LocatorInterface $locator,
        protected EavHelper $eavHelper
    ) {
    }

    /**
     * @inheritdoc
     *
     * Changed custom_entity attribute value type to array.
     */
    public function modifyData(array $data): array
    {
        if (!$this->locator->getEntity()->getId()) {
            return $data;
        }

        $entityId = $this->locator->getEntity()->getId();

        foreach (array_keys($this->getGroups()) as $groupCode) {
            $attributes = !empty($this->getAttributes()[$groupCode]) ? $this->getAttributes()[$groupCode] : [];

            foreach ($attributes as $attribute) {
                if (
                    $attribute->getFrontendInput() == 'smile_custom_entity_select'
                    && key_exists($attribute->getAttributeCode(), $data[$entityId][self::DATA_SOURCE_DEFAULT])
                ) {
                    $value = $data[$entityId][self::DATA_SOURCE_DEFAULT][$attribute->getAttributeCode()];
                    $data[$entityId][self::DATA_SOURCE_DEFAULT][$attribute->getAttributeCode()] =
                        $value ? explode(',', $value) : [];
                }
            }
        }
        return $data;
    }
    /**
     * @inheritdoc
     *
     * Added custom_entity field configuration.
     */
    public function modifyMeta(array $meta): array
    {
        foreach ($meta as $groupCode => $groupConfig) {
            $meta[$groupCode] = $this->modifyMetaConfig($groupConfig);
        }

        return $meta;
    }

    /**
     * Modification of group config.
     */
    protected function modifyMetaConfig(array $metaConfig): array
    {
        if (isset($metaConfig['children'])) {
            foreach ($metaConfig['children'] as $attributeCode => $attributeConfig) {
                if ($this->startsWith($attributeCode, self::CONTAINER_PREFIX)) {
                    $metaConfig['children'][$attributeCode] = $this->modifyMetaConfig($attributeConfig);
                } elseif (
                    !empty($attributeConfig['arguments']['data']['config']['dataType']) &&
                    $attributeConfig['arguments']['data']['config']['dataType'] === 'smile_custom_entity_select'
                ) {
                    $metaConfig['children'][$attributeCode] = $this->modifyAttributeConfig($attributeConfig);
                }
            }
        }

        return $metaConfig;
    }

    /**
     * Modification of attribute config.
     */
    protected function modifyAttributeConfig(array $attributeConfig): array
    {
        return array_replace_recursive(
            $attributeConfig,
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'component'     => 'Magento_Ui/js/form/element/ui-select',
                            'disableLabel'  => true,
                            'elementTmpl'   => 'ui/grid/filters/elements/ui-select',
                            'filterOptions' => true,
                            'multiple'      => true,
                            'showCheckbox'  => true,
                        ],
                    ],
                ],
            ]
        );
    }

    /**
     * List of attribute groups of the form.
     */
    protected function getGroups(): array
    {
        return $this->eavHelper->getGroups($this->getAttributeSetId());
    }

    /**
     * List of attributes of the form.
     */
    protected function getAttributes(): array
    {
        return $this->eavHelper->getAttributes($this->locator->getEntity(), $this->getAttributeSetId());
    }

    /**
     * Return current attribute set id.
     */
    protected function getAttributeSetId(): int
    {
        return (int) $this->locator->getEntity()->getAttributeSetId();
    }
}
