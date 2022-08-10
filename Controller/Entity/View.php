<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller\Entity;

use Magento\Eav\Api\AttributeSetRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Filter\FilterManager;
use Smile\CustomEntity\Api\CustomEntityRepositoryInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity view controller.
 */
class View extends Action
{
    /**
     * @var CustomEntityRepositoryInterface
     */
    private $customEntityRepository;

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
     * @var AttributeSetRepositoryInterface
     */
    private $attributeSetRepository;

    /**
     * View constructor.
     *
     * @param Context                                             $context                Context.
     * @param CustomEntityRepositoryInterface                     $customEntityRepository Custom entity repository.
     * @param \Magento\Framework\Registry                         $registry               Registry.
     * @param \Magento\Framework\View\Result\PageFactory          $resultPageFactory      Result page factory.
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory   Result forward factory.
     * @param FilterManager                                       $filterManager          Filter manager.
     * @param AttributeSetRepositoryInterface                     $attributeSetRepository Attribute set repository.
     */
    public function __construct(
        Context $context,
        CustomEntityRepositoryInterface $customEntityRepository,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        FilterManager $filterManager,
        AttributeSetRepositoryInterface $attributeSetRepository
    ) {
        parent::__construct($context);
        $this->customEntityRepository = $customEntityRepository;
        $this->registry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        $this->resultForwardFactory = $resultForwardFactory;
        $this->filterManager = $filterManager;
        $this->attributeSetRepository = $attributeSetRepository;
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
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
        $entityId = (int) $this->getRequest()->getParam('entity_id', false);
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
     *
     * @return string
     * @throws NoSuchEntityException
     */
    private function getAttributeSetCode(CustomEntityInterface $entity)
    {
        $attributeSet = $this->attributeSetRepository->get($entity->getAttributeSetId());

        return $this->filterManager->translitUrl($attributeSet->getAttributeSetName());
    }
}
