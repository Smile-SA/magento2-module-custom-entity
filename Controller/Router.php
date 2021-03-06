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
namespace Smile\CustomEntity\Controller;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\RouterInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Smile\CustomEntity\Model\CustomEntity;
use Smile\CustomEntity\Model\CustomEntity\AttributeSet\Url;

/**
 * Custom entity router.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Maxime Leclercq <maxime.leclercq@smile.fr>
 */
class Router implements RouterInterface
{
    /**
     * @var Url
     */
    private $urlSetModel;

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    private $actionFactory;

    /**
     * @var CustomEntity
     */
    private $customEntity;

    /**
     * Router constructor.
     *
     * @param \Magento\Framework\App\ActionFactory $actionFactory Action factory.
     * @param Url                                  $urlSetModel   Attribute set url model.
     * @param CustomEntityInterface                $customEntity  Custom entity model.
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        Url $urlSetModel,
        CustomEntityInterface $customEntity
    ) {
        $this->urlSetModel = $urlSetModel;
        $this->actionFactory = $actionFactory;
        $this->customEntity = $customEntity;
    }

    /**
     * Match application action by request
     *
     * @param RequestInterface|HttpRequest $request Request.
     *
     * @return ActionInterface|null
     */
    // @codingStandardsIgnoreLine Match function is allow in router (MEQP2.Classes.PublicNonInterfaceMethods.PublicMethodFound)
    public function match(RequestInterface $request)
    {
        $requestPath = trim($request->getPathInfo(), '/');
        $requestPathArray = explode('/', $requestPath);
        if (!$this->isValidPath($requestPathArray) || $request->getAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS)) {
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
     *
     * @return int
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    private function matchCustomEntity(array $requestPathArray): int
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
     *
     * @return string
     */
    private function getControllerName(array $requestPathArray)
    {
        return $this->isCustomEntitySet($requestPathArray) ? 'set' : 'entity';
    }

    /**
     * Return true if we want to see a set of custom entity.
     *
     * @param array $requestPathArray Request path array.
     *
     * @return bool
     */
    private function isCustomEntitySet(array $requestPathArray): bool
    {
        return count($requestPathArray) == 1;
    }

    /**
     * Return true if current request is allow into this router.
     *
     * @param array $requestPathArray Request path array.
     *
     * @return bool
     */
    private function isValidPath(array $requestPathArray): bool
    {
        return count($requestPathArray) > 0 && count($requestPathArray) <= 2;
    }
}
