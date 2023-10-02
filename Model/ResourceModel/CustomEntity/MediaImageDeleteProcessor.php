<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity;

use Exception;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Psr\Log\LoggerInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;

class MediaImageDeleteProcessor
{
    protected ResourceConnection $resourceConnection;
    protected WriteInterface $mediaDirectory;
    protected LoggerInterface $logger;
    protected array $mediaAttributes = [];
    protected array $mediaAttributesFrontendType = [
        'image',
    ];

    public function __construct(
        ResourceConnection $resourceConnection,
        Filesystem $filesystem,
        LoggerInterface $logger
    ) {
        $this->resourceConnection = $resourceConnection;
        $this->logger = $logger;
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
    }

    /**
     * Remove image attribute files.
     */
    public function execute(DataObject $object): void
    {
        foreach ($this->getMediaAttributes() as $attributeCode) {
            $mediaFileName = $object->getData($attributeCode);

            if (!$mediaFileName) {
                continue;
            }

            $mediaFileName = ltrim($mediaFileName, '/media');
            $filePath = $this->mediaDirectory->getAbsolutePath($mediaFileName);

            if (!$this->mediaDirectory->isFile($filePath)) {
                continue;
            }

            try {
                $this->deletePhysicalImage($filePath);
            } catch (Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
    }

    /**
     * Delete the physical image.
     */
    protected function deletePhysicalImage(string $filePath): void
    {
        $this->mediaDirectory->delete($filePath);
    }

    /**
     * Returns the media attribute codes of the "custom entity".
     */
    protected function getMediaAttributes(): array
    {
        if (empty($this->mediaAttributes)) {
            $this->mediaAttributes = $this->loadMediaAttributes();
        }

        return $this->mediaAttributes;
    }

    /**
     * Load media attribute codes.
     */
    protected function loadMediaAttributes(): array
    {
        $connection = $this->resourceConnection->getConnection();

        $select = $connection->select()->from(
            ['ea' => $connection->getTableName('eav_attribute')],
            ['attribute_code']
        )->joinLeft(
            ['eet' => $connection->getTableName('eav_entity_type')],
            'ea.entity_type_id = eet.entity_type_id'
        )->where(
            'eet.entity_type_code = ?',
            CustomEntityInterface::ENTITY
        )->where(
            'frontend_input IN (?)',
            $this->mediaAttributesFrontendType
        );

        return $connection->fetchCol($select);
    }
}
