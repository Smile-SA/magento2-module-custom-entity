<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\CustomEntity;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Model\CustomEntity;

/**
 * Custom entity view block.
 */
class View extends Template implements IdentityInterface
{
    private Registry $registry;

    private ImageFactory $imageFactory;

    private CustomEntityInterface $customEntity;

    /**
     * View constructor.
     *
     * @param Template\Context $context Context.
     * @param Registry $registry Registry.
     * @param ImageFactory $imageFactory Image factory.
     * @param array $data Data.
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
     */
    public function getEntity(): ?CustomEntityInterface
    {
        if (!$this->customEntity) {
            $this->customEntity = $this->registry->registry('current_custom_entity');
        }

        return $this->customEntity;
    }

    /**
     * Return custom entity image.
     */
    public function getImage(): ?string
    {
        return $this->imageFactory->create($this->getEntity())->toHtml();
    }

    /**
     * Return unique ID(s) for each object in system.
     *
     * @return string[]|null
     */
    public function getIdentities(): ?array
    {
        return $this->getEntity()->getIdentities();
    }

    /**
     * Prepare layout: add title and breadcrumbs.
     *
     * @return $this|Template
     * @throws LocalizedException
     * @throws NoSuchEntityException
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
     * @throws LocalizedException
     */
    private function setPageTitle(): self
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
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    private function setBreadcrumbs(): self
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
