<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Set;

use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;

/**
 * Attribute set view controller.
 */
class View implements HttpGetActionInterface
{
    private AttributeSetRepositoryInterface $attributeSetRepository;

    private Registry $registry;

    private PageFactory $resultPageFactory;

    private ForwardFactory $resultForwardFactory;

    private FilterManager $filterManager;

    private RequestInterface $request;

    /**
     * View constructor.
     *
     * @param AttributeSetRepositoryInterface $attributeSetRepository Attribute set repository.
     * @param Registry $registry Registry.
     * @param PageFactory $resultPageFactory Result page factory.
     * @param ForwardFactory $resultForwardFactory Result forward factory.
     * @param FilterManager $filterManager Filter manager.
     */
    public function __construct(
        AttributeSetRepositoryInterface $attributeSetRepository,
        Registry $registry,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        FilterManager $filterManager,
        RequestInterface $request
    ) {
        $this->attributeSetRepository = $attributeSetRepository;
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->filterManager = $filterManager;
        $this->request = $request;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return ResultInterface|ResponseInterface
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
        $attributeSetId = (int) $this->request->getParam('entity_id', false);
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
     */
    private function getAttributeSetCode(AttributeSetInterface $attributeSet): string
    {
        return $this->filterManager->translitUrl($attributeSet->getAttributeSetName());
    }
}
