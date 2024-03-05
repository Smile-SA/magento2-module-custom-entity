<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\Sitemap;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;
use Magento\Sitemap\Model\ItemProvider\ItemProviderInterface;
use Magento\Sitemap\Model\SitemapItemInterfaceFactory;
use Smile\CustomEntity\Api\CustomEntityRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Model\Sitemap\ConfigReader;
use Smile\ScopedEav\Api\Data\EntityInterface;

/**
 * Sitemap custom entity items provider.
 */
class CustomEntityProvider implements ItemProviderInterface
{
    protected SitemapItemInterfaceFactory $itemFactory;

    protected ConfigReader $configReader;

    protected CustomEntityRepositoryInterface $customEntityRepository;

    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    protected UrlInterface $urlBuilder;

    public function __construct(
        SitemapItemInterfaceFactory $itemFactory,
        ConfigReader $configReader,
        CustomEntityRepositoryInterface $customEntityRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        UrlInterface $urlBuilder
    ) {
        $this->itemFactory = $itemFactory;
        $this->configReader = $configReader;
        $this->customEntityRepository = $customEntityRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * @inheritdoc
     */
    public function getItems($storeId)
    {
        if (!$this->configReader->isIncludeInSitemap((int) $storeId)) {
            return [];
        }

        $entityTypes = $this->configReader->getEntityTypes((int) $storeId);

        $search = $this->searchCriteriaBuilder->addFilter(EntityInterface::IS_ACTIVE, '1')
            ->addFilter(EntityInterface::ATTRIBUTE_SET_ID, $entityTypes, 'in')
            ->create();

        $collection = $this->customEntityRepository->getList($search);
        $isImageInclude = $this->configReader->isImageInclude((int) $storeId);
        $priority = $this->configReader->getPriority((int) $storeId);
        $changeFrequency = $this->configReader->getChangeFrequency((int) $storeId);

        $items = array_map(function ($item) use ($isImageInclude, $priority, $changeFrequency) {
            return $this->itemFactory->create([
                'url' => $item->getUrlPath(),
                'updatedAt' => $item->getUpdatedAt(),
                'images' => $isImageInclude ? $this->getImages($item) : null,
                'priority' => $priority,
                'changeFrequency' => $changeFrequency,
            ]);
        }, $collection->getItems());

        return $items;
    }

    /**
     * Get images object for sitemap.
     */
    protected function getImages(CustomEntityInterface $entity): ?DataObject
    {
        $image = $entity->getImageUrl(EntityInterface::IMAGE);

        if (!$image) {
            return null;
        }

        $imageUrl = $this->urlBuilder->getDirectUrl(ltrim($image, "/"));
        $imageCollection = [new DataObject(['url' => $imageUrl])];

        return new DataObject([
            'collection' => $imageCollection,
            'title' => $entity->getName(),
            'thumbnail' => $imageUrl,
        ]);
    }
}
