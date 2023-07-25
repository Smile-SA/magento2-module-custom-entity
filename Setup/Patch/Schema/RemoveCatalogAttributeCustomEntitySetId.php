<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Setup\Patch\Schema;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\Setup\Patch\SchemaPatchInterface;
use Smile\CustomEntity\Setup\Patch\Data\CustomEntityAttributeSetIdMigration;

class RemoveCatalogAttributeCustomEntitySetId implements SchemaPatchInterface
{
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
            $connection->tableColumnExists(
                $catalogEavTable,
                CustomEntityAttributeSetIdMigration::ATTRIBUTE_SET_ID_COLUMN
            ) &&
            $connection->tableColumnExists(
                $eavTable,
                CustomEntityAttributeSetIdMigration::ATTRIBUTE_SET_ID_COLUMN
            )
        ) {
            $connection->dropColumn(
                $catalogEavTable,
                CustomEntityAttributeSetIdMigration::ATTRIBUTE_SET_ID_COLUMN
            );
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [CustomEntityAttributeSetIdMigration::class];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }
}
