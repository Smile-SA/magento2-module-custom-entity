<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Block\CustomEntity;

use Smile\CustomEntity\Api\Data\CustomEntityInterface;

/**
 * Custom entity image block factory.
 */
class ImageFactory
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * @var string
     */
    private $instanceName;

    /**
     * ImageFactory constructor.
     *
     * @param \Magento\Framework\ObjectManagerInterface $objectManager Object manager.
     * @param string                                    $instanceName  Image instance name.
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        $instanceName = Image::class
    ) {
        $this->objectManager = $objectManager;
        $this->instanceName = $instanceName;
    }

    /**
     * Return custom entity image block.
     *
     * @param CustomEntityInterface $entity Current custom entity.
     *
     * @return Image
     */
    public function create(CustomEntityInterface $entity)
    {
        $data = [
            'data' => [
                'template' => 'Smile_CustomEntity::custom_entity/image.phtml',
                'image_url' => $entity->getImageUrl('image'),
                'image_alt' => $entity->getName(),
            ],
        ];

        // @codingStandardsIgnoreLine Factory class (MEQP2.Classes.ObjectManager.ObjectManagerFound)
        return $this->objectManager->create($this->instanceName, $data);
    }
}
