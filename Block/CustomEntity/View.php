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

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Model\CustomEntity;

/**
 * Custom entity view block.
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
     * @var ImageFactory
     */
    private $imageFactory;

    /**
     * @var CustomEntityInterface
     */
    private $customEntity;

    /**
     * View constructor.
     *
     * @param Template\Context $context      Context.
     * @param Registry         $registry     Registry.
     * @param ImageFactory     $imageFactory Image factory.
     * @param array            $data         Data.
     */
    public function __construct(
        Template\Context $context,
        Registry $registry,
        ImageFactory $imageFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->registry = $registry;
        $this->imageFactory = $imageFactory;
    }

    /**
     * Return current custom entity.
     *
     * @return CustomEntityInterface|null
     */
    public function getEntity()
    {
        if (!$this->customEntity) {
            $this->customEntity = $this->registry->registry('current_custom_entity');
        }

        return $this->customEntity;
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
     * Return unique ID(s) for each object in system.
     *
     * @return string[]
     */
    public function getIdentities()
    {
        return $this->getEntity()->getIdentities();
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
        if ($this->getEntity()) {
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
        $customEntity = $this->getEntity();
        $titleBlock = $this->getLayout()->getBlock('page.main.title');
        $pageTitle = $customEntity->getName();
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
            /** @var CustomEntity $customEntity */
            $customEntity = $this->getEntity();
            $homeUrl = $this->_storeManager->getStore()->getBaseUrl();
            $breadcrumbsBlock->addCrumb(
                'home',
                ['label' => __('Home'), 'title' => __('Go to Home Page'), 'link' => $homeUrl]
            );
            $breadcrumbsBlock->addCrumb(
                'set',
                [
                    'label' => $customEntity->getAttributeSet()->getAttributeSetName(),
                    'title' => $customEntity->getAttributeSet()->getAttributeSetName(),
                    'link' => $this->_urlBuilder->getDirectUrl($customEntity->getAttributeSetUrlKey()),
                ]
            );
            $breadcrumbsBlock->addCrumb(
                'custom_entity',
                ['label' => $customEntity->getName(), 'title' => $customEntity->getName()]
            );
        }

        return $this;
    }
}
