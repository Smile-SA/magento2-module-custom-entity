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
namespace Smile\CustomEntity\Block\Set\Entity;

use Magento\Framework\View\Element\Template;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Block\CustomEntity\ImageFactory;

/**
 * Attribute set custom entity renderer.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Maxime Leclercq <maxime.leclercq@smile.fr>
 *
 * @method Renderer setEntity(CustomEntityInterface $entity)
 * @method CustomEntityInterface getEntity()
 */
class Renderer extends Template
{
    /**
     * @var ImageFactory
     */
    private $imageFactory;

    /**
     * Renderer constructor.
     *
     * @param Template\Context $context      Context.
     * @param ImageFactory     $imageFactory Custom entity image block factory.
     * @param array            $data         Block data.
     */
    public function __construct(
        Template\Context $context,
        ImageFactory $imageFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->imageFactory = $imageFactory;
    }

    /**
     * Return custom entity image.
     *
     * @return string
     */
    public function getImage()
    {
        return $this->imageFactory->create($this->getEntity())->toHtml();
    }
}
