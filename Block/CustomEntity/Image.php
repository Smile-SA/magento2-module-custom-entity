<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\CustomEntity
 * @author    Maxime Leclercq <maxime.leclercq@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Smile\CustomEntity\Block\CustomEntity;

use Magento\Framework\View\Element\Template;

/**
 * Custom entity image block.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Maxime Leclercq <maxime.leclercq@smile.fr>
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
     * @param array            $data    Block data.
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
