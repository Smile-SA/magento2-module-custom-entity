<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\Sitemap;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

/**
 * Sitemap config reader.
 */
class ConfigReader
{
    public const XML_PATH_INCLUDE_IN_SITEMAP = 'sitemap/smile_custom_entity/include_in_sitemap';

    public const XML_PATH_INCLUDE_IMAGE_IN_SITEMAP = 'sitemap/smile_custom_entity/include_image';

    public const XML_PATH_ENTITY_TYPES = 'sitemap/smile_custom_entity/entity_types';

    public const XML_PATH_CHANGE_FREQUENCY = 'sitemap/smile_custom_entity/changefreq';

    public const XML_PATH_PRIORITY = 'sitemap/smile_custom_entity/priority';

    protected ScopeConfigInterface $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get is custom entity include into sitemap.
     */
    public function isIncludeInSitemap(int $storeId): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_INCLUDE_IN_SITEMAP,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get is image include into sitemap.
     */
    public function isImageInclude(int $storeId): bool
    {
        return (bool) $this->scopeConfig->getValue(
            self::XML_PATH_INCLUDE_IMAGE_IN_SITEMAP,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get entity types to include into sitemap.
     */
    public function getEntityTypes(int $storeId): array
    {
        $types = (string) $this->scopeConfig->getValue(
            self::XML_PATH_ENTITY_TYPES,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        return $types ? explode(',', $types) : [];
    }

    /**
     * Get change frequency.
     */
    public function getChangeFrequency(int $storeId): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_CHANGE_FREQUENCY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get priority.
     */
    public function getPriority(int $storeId): string
    {
        return (string) $this->scopeConfig->getValue(
            self::XML_PATH_PRIORITY,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
