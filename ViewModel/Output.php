<?php

declare(strict_types=1);

namespace Smile\CustomEntity\ViewModel;

use Magento\Catalog\Helper\Output as MagentoOutput;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity output helper.
 */
class Output extends MagentoOutput implements ArgumentInterface
{
    /**
     * Prepare custom entity attribute html output
     *
     * @param CustomEntityInterface $customEntity Custom Entity.
     * @param string $attributeHtml Attribute html value.
     * @param string $attributeName Attribute name.
     * @throws LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function customEntityAttribute(
        CustomEntityInterface $customEntity,
        string $attributeHtml,
        string $attributeName
    ): ?string {
        $attribute = $this->_eavConfig->getAttribute(CustomEntityInterface::ENTITY, $attributeName);
        if (
            $attribute->getId() &&
            $attribute->getFrontendInput() != 'image' &&
            (!$attribute->getIsHtmlAllowedOnFront() &&
                !$attribute->getIsWysiwygEnabled())
        ) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if (
            $attributeHtml !== null
            && $attribute->getIsHtmlAllowedOnFront()
            && $attribute->getIsWysiwygEnabled()
            && $this->isDirectivesExists($attributeHtml)
        ) {
            $attributeHtml = $this->_getTemplateProcessor()->filter($attributeHtml);
        }

        $attributeHtml = $this->process(
            'customEntityAttribute',
            $attributeHtml,
            ['product' => $customEntity, 'attribute' => $attributeName]
        );

        return $attributeHtml;
    }
}
