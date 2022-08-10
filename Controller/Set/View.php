<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Set;

use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterManager;

/**
 * Attribute set view controller.
 */
class View extends Action
{
    /**
     * @var \Magento\Eav\Api\AttributeSetRepositoryInterface
     */
    private $attributeSetRepository;

    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    private $resultPageFactory;

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    private $resultForwardFactory;

    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * View constructor.
     *
     * @param Context                                             $context                Context.
     * @param \Magento\Eav\Api\AttributeSetRepositoryInterface    $attributeSetRepository Attribute set repository.
     * @param \Magento\Framework\Registry                         $registry               Registry.
     * @param \Magento\Framework\View\Result\PageFactory          $resultPageFactory      Result page factory.
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory   Result forward factory.
     * @param FilterManager                                       $filterManager          Filter manager.
     */
    public function __construct(
        Context $context,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSetRepository,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        FilterManager $filterManager
    ) {
        parent::__construct($context);
        $this->attributeSetRepository = $attributeSetRepository;
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->filterManager = $filterManager;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        $attributeSet = $this->initSet();
        if (!$attributeSet) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $page = $this->resultPageFactory->create();
        $page->addPageLayoutHandles(['type' => $this->getAttributeSetCode($attributeSet)], null, false);
        $page->addPageLayoutHandles(['id' => $attributeSet->getAttributeSetId()]);

        return $page;
    }

    /**
     * Return attribute set and append it into register.
     *
     * @return bool|AttributeSetInterface
     */
    private function initSet()
    {
        $attributeSetId = (int) $this->getRequest()->getParam('entity_id', false);
        if (!$attributeSetId) {
            return false;
        }

        try {
            $attributeSet = $this->attributeSetRepository->get($attributeSetId);
            $this->registry->register('current_attribute_set', $attributeSet);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return $attributeSet;
    }

    /**
     * Return attribute set code.
     *
     * @param AttributeSetInterface $attributeSet Current attribute set.
     *
     * @return string
     */
    private function getAttributeSetCode(AttributeSetInterface $attributeSet)
    {
        return $this->filterManager->translitUrl($attributeSet->getAttributeSetName());
    }
}
