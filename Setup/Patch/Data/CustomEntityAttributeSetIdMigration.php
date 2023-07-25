<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Setup\Patch\Data;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Migrate custom_entity_attribute_set_id column data from `catalog_eav_attribute` to `eav_attribute` table.
 */
class CustomEntityAttributeSetIdMigration implements DataPatchInterface
{
    /**
     * Attribute set id column name.
     */
    public const ATTRIBUTE_SET_ID_COLUMN = 'custom_entity_attribute_set_id';

    /**
     * Resource connection.
     */
    protected ResourceConnection $resourceConnection;

    /**
     * Constructor.
     */
    public function __construct(
        ResourceConnection $resourceConnection
    ) {
        $this->resourceConnection = $resourceConnection;
    }

    /**
     * @inheritdoc
     */
    public function apply()
    {
        $connection  = $this->resourceConnection->getConnection();

        $catalogEavTable = $connection->getTableName('catalog_eav_attribute');
        $eavTable = $connection->getTableName('eav_attribute');

        if (
            $connection->tableColumnExists($catalogEavTable, self::ATTRIBUTE_SET_ID_COLUMN) &&
            $connection->tableColumnExists($eavTable, self::ATTRIBUTE_SET_ID_COLUMN)
        ) {
            $select = $connection->select()
                ->from(['cea' => 'catalog_eav_attribute'], [self::ATTRIBUTE_SET_ID_COLUMN])
                ->where('ea.attribute_id = cea.attribute_id');

            $connection->query(
                $connection->updateFromSelect($select, ['ea' => 'eav_attribute'])
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
