<?php

namespace Smile\CustomEntity\Plugin\Model\RessourceModel\Eav;

use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Magento\Catalog\Api\Data\EavAttributeInterface;

class Attribute
{
    /**
     * Allow Smile custom entity for rule condition
     *
     * @return bool
     */
    public function afterIsAllowedForRuleCondition(EavAttribute $subject, $result)
    {
        if ($subject->getData(EavAttributeInterface::IS_VISIBLE)
            && $subject->getFrontendInput() == 'smile_custom_entity') {
            return true;
        }

        return $result;
    }
}
