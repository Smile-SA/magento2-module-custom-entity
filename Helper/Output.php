<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Helper;

use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Model\CustomEntity;

/**
 * Custom entity output helper.
 */
class Output extends \Magento\Catalog\Helper\Output
{
    /**
     * Prepare custom entity attribute html output
     *
     * @param CustomEntityInterface $customEntity  Custom Entity.
     * @param string                $attributeHtml Attribute html value.
     * @param string                $attributeName Attribute name.
     *
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     */
    public function customEntityAttribute(
        CustomEntityInterface $customEntity,
        string $attributeHtml,
        string $attributeName
    ) {
        $attribute = $this->_eavConfig->getAttribute(CustomEntity::ENTITY, $attributeName);
        if ($attribute &&
            $attribute->getId() &&
            $attribute->getFrontendInput() != 'image' &&
            (!$attribute->getIsHtmlAllowedOnFront() &&
                !$attribute->getIsWysiwygEnabled())
        ) {
            if ($attribute->getFrontendInput() == 'textarea') {
                $attributeHtml = nl2br($attributeHtml);
            }
        }
        if ($attributeHtml !== null
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
