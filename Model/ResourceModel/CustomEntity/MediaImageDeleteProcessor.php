<?php

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DataObject;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Smile\CustomEntity\Api\Data\CustomEntityInterface;
use Exception;
use Psr\Log\LoggerInterface;

class MediaImageDeleteProcessor
{
    /**
     * Resource connection.
     */
    protected ResourceConnection $resourceConnection;

    /**
     * Media directory.
     */
    protected WriteInterface $mediaDirectory;

    /**
     * Logger.
     */
    protected LoggerInterface $logger;

    /**
     * Media attributes frontend type.
     */
    protected array $mediaAttributesFrontendType = [
        'image'
    ];

    /**
     * Media attributes.
     */
    protected array $mediaAttributes = [];

    /**
     * Constructor.
     */
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

            $mediaFileName = ltrim($mediaFileName,"/media");
            $filePath = $this->mediaDirectory->getAbsolutePath($mediaFileName);
            
            if (!$this->mediaDirectory->isFile($filePath)) {
                continue;
            }

            try {
                $this->deletePhysicalImage($filePath);
            } catch (Exception $e) {
                $this->logger->critical($e);
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