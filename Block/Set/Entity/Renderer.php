<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Set\Entity;

use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\View\Element\Template;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Block\CustomEntity\ImageFactory;

/**
 * Attribute set custom entity renderer.
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

    /**
     * Return entity url.
     *
     * @return string
     */
    public function getEntityUrl()
    {
        return $this->_urlBuilder->getDirectUrl($this->getEntity()->getUrlPath());
    }
}
