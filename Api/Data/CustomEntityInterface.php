<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Api\Data;

use Magento\Framework\DataObject\IdentityInterface;
use Smile\ScopedEav\Api\Data\EntityInterface;

interface CustomEntityInterface extends EntityInterface, IdentityInterface
{
    /**
     * Entity code. Can be used as part of method name for entity processing.
     */
    public const ENTITY = 'smile_custom_entity';

    /**#@+
     * Constants defined for keys of data array
     */
    public const URL_KEY = 'url_key';
    /**#@-*/

    /**
     * Returns custom entity url key.
     *
     * @return string|null
     */
    public function getUrlKey(): ?string;

    /**
     * Set custom entity url key.
     *
     * @param string $urlKey Url key
     * @return $this
     */
    public function setUrlKey(string $urlKey): self;

    /**
     * Returns custom entity url path.
     *
     * @return string|null
     */
    public function getUrlPath(): ?string;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \Smile\CustomEntity\Api\Data\CustomEntityExtensionInterface|null
     */
    public function getExtensionAttributes(): ?\Smile\CustomEntity\Api\Data\CustomEntityExtensionInterface;

    /**
     * Set an extension attributes object.
     *
     * @param \Smile\CustomEntity\Api\Data\CustomEntityExtensionInterface $extensionAttributes Extension attributes.
     * @return $this
     */
    public function setExtensionAttributes(
        \Smile\CustomEntity\Api\Data\CustomEntityExtensionInterface $extensionAttributes
    ): self;
}
