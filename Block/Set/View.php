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
namespace Smile\CustomEntity\Block\Set;

use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\RendererList;
use Magento\Framework\View\Element\Template;
use Magento\Framework\Registry;
use Smile\CustomEntity\Api\CustomEntityRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Block\Html\Pager;

/**
 * Attribute set view block.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Maxime Leclercq <maxime.leclercq@smile.fr>
 */
class View extends Template implements IdentityInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    /**
     * @var CustomEntityRepositoryInterface
     */
    private $customEntityRepository;

    /**
     * @var \Smile\CustomEntity\Api\Data\CustomEntityInterface[]|null
     */
    private $entities;

    /**
     * View constructor.
     *
     * @param Template\Context                $context                      Context.
     * @param Registry                        $registry                     Registry.
     * @param CustomEntityRepositoryInterface $customEntityRepository       Custom entity repository.
     * @param SearchCriteriaBuilderFactory    $searchCriteriaBuilderFactory Search criteria builder factory.
     * @param array                           $data                         Block data.
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
     * @return \Smile\CustomEntity\Api\Data\CustomEntityInterface[]
     */
    public function getEntities()
    {
        if (!$this->entities) {
            /** @var SearchCriteriaBuilder $searchCriteriaBuilder */
            $searchCriteriaBuilder = $this->searchCriteriaBuilderFactory->create();
            $searchCriteriaBuilder->addFilter(
                'attribute_set_id',
                $this->getAttributeSet()->getAttributeSetId()
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
     *
     * @return string
     */
    public function getEntityHtml(CustomEntityInterface $entity)
    {
        return $this->getEntityRenderer($this->getAttributeSet()->getAttributeSetName())->setEntity($entity)->toHtml();
    }

    /**
     * Return current attribute set.
     *
     * @return AttributeSetInterface|null
     */
    public function getAttributeSet()
    {
        return $this->registry->registry('current_attribute_set');
    }

    /**
     * Return block identities.
     *
     * @return array|string[]
     */
    public function getIdentities()
    {
        $identities = [];
        foreach ($this->getEntities() as $entity) {
            $identities = array_merge($identities, $entity->getIdentities());
        }

        return $identities;
    }

    /**
     * Return pager.
     *
     * @return Pager
     */
    public function getPager()
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
     *
     * @return bool|\Magento\Framework\View\Element\AbstractBlock
     */
    private function getEntityRenderer($attributeSetCode = null)
    {
        if ($attributeSetCode === null) {
            $attributeSetCode = 'default';
        }
        /** @var RendererList $rendererList */
        $rendererList = $this->getChildBlock('renderer.list');

        return $rendererList->getRenderer($attributeSetCode, 'default');
    }
}
