<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Entity;

use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterManager;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Smile\CustomEntity\Api\CustomEntityRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity view controller.
 */
class View implements HttpGetActionInterface
{
    private CustomEntityRepositoryInterface $customEntityRepository;

    private Registry $registry;

    private PageFactory $resultPageFactory;

    private ForwardFactory $resultForwardFactory;

    private FilterManager $filterManager;

    private AttributeSetRepositoryInterface $attributeSetRepository;

    private RequestInterface $request;

    /**
     * View constructor.
     *
     * @param CustomEntityRepositoryInterface $customEntityRepository Custom entity repository.
     * @param Registry $registry Registry.
     * @param PageFactory $resultPageFactory Result page factory.
     * @param ForwardFactory $resultForwardFactory Result forward factory.
     * @param FilterManager $filterManager Filter manager.
     * @param AttributeSetRepositoryInterface $attributeSetRepository Attribute set repository.
     */
    public function __construct(
        CustomEntityRepositoryInterface $customEntityRepository,
        Registry $registry,
        PageFactory $resultPageFactory,
        ForwardFactory $resultForwardFactory,
        FilterManager $filterManager,
        AttributeSetRepositoryInterface $attributeSetRepository,
        RequestInterface $request
    ) {
        $this->customEntityRepository = $customEntityRepository;
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->filterManager = $filterManager;
        $this->attributeSetRepository = $attributeSetRepository;
        $this->request = $request;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return ResultInterface|ResponseInterface
     * @throws NoSuchEntityException
     */
    public function execute()
    {
        $entity = $this->initEntity();
        if (!$entity || !$entity->getIsActive()) {
            return $this->resultForwardFactory->create()->forward('noroute');
        }

        $page = $this->resultPageFactory->create();
        $page->addPageLayoutHandles(['type' => $this->getAttributeSetCode($entity)], null, false);
        $page->addPageLayoutHandles(['id' => $entity->getId()]);

        return $page;
    }

    /**
     * Return custom entity and append it into register.
     *
     * @return bool|CustomEntityInterface
     */
    private function initEntity()
    {
        $entityId = (int) $this->request->getParam('entity_id', false);
        if (!$entityId) {
            return false;
        }

        try {
            $customEntity = $this->customEntityRepository->get($entityId);
            $this->registry->register('current_custom_entity', $customEntity);
        } catch (NoSuchEntityException $e) {
            return false;
        }

        return $customEntity;
    }

    /**
     * Return attribute set code.
     *
     * @param CustomEntityInterface $entity Custom entity.
     */
    private function getAttributeSetCode(CustomEntityInterface $entity): string
    {
        $attributeSet = $this->attributeSetRepository->get($entity->getAttributeSetId());
        return $this->filterManager->translitUrl($attributeSet->getAttributeSetName());
    }
}
