<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Controller;

use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\NotFoundException;
use Smile\CustomEntity\Model\CustomEntity;
use Smile\CustomEntity\Model\CustomEntity\AttributeSet\Url;

/**
 * Custom entity router.
 */
class Router implements RouterInterface
{
    private Url $urlSetModel;

    private ActionFactory $actionFactory;

    private CustomEntity $customEntity;

    /**
     * Router constructor.
     *
     * @param ActionFactory $actionFactory Action factory.
     * @param Url $urlSetModel Attribute set url model.
     * @param CustomEntity $customEntity Custom entity model.
     */
    public function __construct(
        ActionFactory $actionFactory,
        Url $urlSetModel,
        CustomEntity $customEntity
    ) {
        $this->urlSetModel = $urlSetModel;
        $this->actionFactory = $actionFactory;
        $this->customEntity = $customEntity;
    }

    /**
     * Match application action by request
     *
     * @param RequestInterface|HttpRequest $request Request.
     * @return ActionInterface|null
     */
    // @codingStandardsIgnoreLine Match function is allow in router (MEQP2.Classes.PublicNonInterfaceMethods.PublicMethodFound)
    public function match(RequestInterface $request)
    {
        /** @var HttpRequest $request */
        $requestPath = trim($request->getPathInfo(), '/');
        $requestPathArray = explode('/', $requestPath);
        if (
            !$this->isValidPath($requestPathArray)
            || $request->getAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS)
        ) {
            // Continuing with processing of this URL.
            return null;
        }

        try {
            $entityId = $this->matchCustomEntity($requestPathArray);
            $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $requestPath)
                ->setModuleName('custom_entity')
                ->setControllerName($this->getControllerName($requestPathArray))
                ->setActionName('view')
                ->setParam('entity_id', $entityId);
        } catch (\Exception $e) {
            // Continuing with processing of this URL.
            return null;
        }

        return $this->actionFactory->create(
            \Magento\Framework\App\Action\Forward::class
        );
    }

    /**
     * Match custom entity.
     *
     * @param array $requestPathArray Request path array
     * @return mixed
     * @throws NoSuchEntityException
     * @throws NotFoundException
     */
    private function matchCustomEntity(array $requestPathArray)
    {
        $entityId = $customEntitySetId = $this->urlSetModel->checkIdentifier(array_shift($requestPathArray));
        if (!empty($requestPathArray) && $customEntitySetId) {
            $entityId = $this->customEntity->checkIdentifier(current($requestPathArray), $entityId);
        }

        return $entityId;
    }

    /**
     * Return controller name.
     *
     * @param array $requestPathArray Request path array.
     */
    private function getControllerName(array $requestPathArray): string
    {
        return $this->isCustomEntitySet($requestPathArray) ? 'set' : 'entity';
    }

    /**
     * Return true if we want to see a set of custom entity.
     *
     * @param array $requestPathArray Request path array.
     */
    private function isCustomEntitySet(array $requestPathArray): bool
    {
        return count($requestPathArray) == 1;
    }

    /**
     * Return true if current request is allow into this router.
     *
     * @param array $requestPathArray Request path array.
     */
    private function isValidPath(array $requestPathArray): bool
    {
        return count($requestPathArray) > 0 && count($requestPathArray) <= 2;
    }
}
