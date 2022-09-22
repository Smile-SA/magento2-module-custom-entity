<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\CustomEntity\AttributeSet;

use Magento\Framework\Exception\NotFoundException;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\AttributeSet\Url as UrlResourceModel;

/**
 * Attribute set url model.
 */
class Url
{
    private UrlResourceModel $urlResourceModel;

    /**
     * Url constructor.
     *
     * @param UrlResourceModel $urlResourceModel Attribute set url resource model.
     */
    public function __construct(
        UrlResourceModel $urlResourceModel
    ) {
        $this->urlResourceModel = $urlResourceModel;
    }

    /**
     * Return attribute set id from url key.
     *
     * @param string $urlKey Attribute set url key.
     * @throws NotFoundException
     */
    public function checkIdentifier(string $urlKey): ?int
    {
        return $this->urlResourceModel->checkIdentifier($urlKey);
    }
}
