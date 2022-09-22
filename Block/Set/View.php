<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\Set;

use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\RendererList;
use Magento\Framework\View\Element\Template;
use Smile\CustomEntity\Api\CustomEntityRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Block\Html\Pager;
use Smile\CustomEntity\Model\CustomEntity;

/**
 * Attribute set view block.
 */
class View extends Template implements IdentityInterface
{
    private Registry $registry;

    private SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory;

    private CustomEntityRepositoryInterface $customEntityRepository;

    /**
     * @var CustomEntityInterface[]|null
     */
    private ?array $entities = null;

    /**
     * View constructor.
     *
     * @param Template\Context $context Context.
     * @param Registry $registry Registry.
     * @param CustomEntityRepositoryInterface $customEntityRepository Custom entity repository.
     * @param SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory Search criteria builder factory.
     * @param array $data Block data.
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        CustomEntityRepositoryInterface $customEntityRepository,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->customEntityRepository = $customEntityRepository;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * Return custom entities.
     *
     * @return CustomEntityInterface[]|null
     */
    public function getEntities(): ?array
    {
        if (!$this->entities) {
            /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
            $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
            $searchCriteriaBuilder->addFilter(
                'attribute_set_id',
                $this->getAttributeSet()->getAttributeSetId()
            );
            $searchCriteriaBuilder->addFilter(
                'is_active',
                true
            );
            $this->getPager()->addCriteria($searchCriteriaBuilder);
            $searchResult = $this->customEntityRepository->getList($searchCriteriaBuilder->create());
            $this->getPager()->setSearchResult($searchResult);

            $this->entities = $searchResult->getItems();
        }

        return $this->entities;
    }

    /**
     * Return custom entity html.
     *
     * @param CustomEntityInterface $entity Custom entity.
     */
    public function getEntityHtml(CustomEntityInterface $entity): ?string
    {
        return $this->getEntityRenderer($this->getAttributeSet()->getAttributeSetName())->setEntity($entity)->toHtml();
    }

    /**
     * Return current attribute set.
     */
    public function getAttributeSet(): ?AttributeSetInterface
    {
        return $this->registry->registry('current_attribute_set');
    }

    /**
     * Return block identities.
     *
     * @return array|string[]
     */
    public function getIdentities(): array
    {
        $identities = [];
        foreach ($this->getEntities() as $entity) {
            $identities = array_merge($identities, $entity->getIdentities());
        }
        $identities[] = CustomEntity::CACHE_CUSTOM_ENTITY_SET_TAG . '_' . $this->getAttributeSet()->getAttributeSetId();

        return $identities;
    }

    /**
     * Return pager.
     */
    public function getPager(): Pager
    {
        return $this->getChildBlock('pager');
    }

    /**
     * Prepare layout: add title and breadcrumbs.
     *
     * @return $this|Template
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getAttributeSet()) {
            $this->setPageTitle()
                ->setBreadcrumbs();
        }

        return $this;
    }

    /**
     * Set the current page title.
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function setPageTitle()
    {
        $attributeSet = $this->getAttributeSet();
        $titleBlock = $this->getLayout()->getBlock('page.main.title');
        $pageTitle = $attributeSet->getAttributeSetName();
        if ($titleBlock) {
            $titleBlock->setPageTitle($pageTitle);
        }
        $this->pageConfig->getTitle()->set(__($pageTitle));

        return $this;
    }

    /**
     * Build breadcrumbs for the current page.
     *
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function setBreadcrumbs()
    {
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbsBlock) {
            $attributeSet = $this->getAttributeSet();
            $homeUrl = $this->_storeManager->getStore()->getBaseUrl();
            $breadcrumbsBlock->addCrumb(
                'home',
                ['label' => __('Home'), 'title' => __('Go to Home Page'), 'link' => $homeUrl]
            );
            $breadcrumbsBlock->addCrumb(
                'attribute_set',
                ['label' => $attributeSet->getAttributeSetName(), 'title' => $attributeSet->getAttributeSetName()]
            );
        }

        return $this;
    }

    /**
     * Return custom entity renderer.
     *
     * @param string|null $attributeSetCode Attribute set code.
     * @return bool|\Magento\Framework\View\Element\AbstractBlock
     */
    private function getEntityRenderer(?string $attributeSetCode = null)
    {
        if ($attributeSetCode === null) {
            $attributeSetCode = 'default';
        }
        /** @var RendererList $rendererList */
        $rendererList = $this->getChildBlock('renderer.list');

        return $rendererList->getRenderer($attributeSetCode, 'default');
    }
}
