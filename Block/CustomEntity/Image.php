<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\CustomEntity;

use Magento\Framework\View\Element\Template;

/**
 * Custom entity image block.
 *
 * @method string getImageUrl()
 * @method string getImageAlt()
 */
class Image extends Template
{
    /**
     * Image constructor.
     *
     * @param Template\Context $context Context.
     * @param array $data Block data.
     */
    // @codingStandardsIgnoreLine Assign block template (MEQP2.Classes.ConstructorOperations.CustomOperationsFound)
    public function __construct(Template\Context $context, array $data = [])
    {
        if (array_key_exists('template', $data)) {
            $this->setTemplate($data['template']);
            unset($data['template']);
        }

        parent::__construct($context, $data);
    }
}
