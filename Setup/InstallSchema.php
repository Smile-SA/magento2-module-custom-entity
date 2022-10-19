<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Setup;

use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Smile\ScopedEav\Setup\SchemaSetupFactory;

/**
 * Custom entity schema setup.
 */
class InstallSchema implements InstallSchemaInterface
{
    private SchemaSetupFactory $schemaSetupFactory;

    /**
     * Constructor.
     *
     * @param SchemaSetupFactory $schemaSetupFactory Scoped EAV schema setup factory.
     */
    public function __construct(SchemaSetupFactory $schemaSetupFactory)
    {
        $this->schemaSetupFactory = $schemaSetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    // @codingStandardsIgnoreLine Context param not used (Generic.CodeAnalysis.UnusedFunctionParameter.Found)
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        // Start setup.
        $setup->startSetup();

        $schemaSetup = $this->schemaSetupFactory->create(['setup' => $setup]);
        $connection  = $setup->getConnection();
        $entityTable = 'smile_custom_entity';

        // Create additional attribute config table.
        $table = $this->addAttributeConfigFields($schemaSetup->getAttributeAdditionalTable($entityTable))
            ->setComment('Custom Entity Attribute');
        $connection->createTable($table);

        // Create the custom entity main table.
        $table = $schemaSetup->getEntityTable($entityTable)->setComment('Custom Entity Table');
        $connection->createTable($table);

        // Create the custom entity attribute backend tables (int, varchar, decimal, text and datetime).
        $table = $schemaSetup->getEntityAttributeValueTable($entityTable, Table::TYPE_INTEGER, null, 'int')
            ->setComment('Custom Entity Backend Table (int).');
        $connection->createTable($table);

        $table = $schemaSetup->getEntityAttributeValueTable($entityTable, Table::TYPE_DECIMAL, '12,4')
            ->setComment('Custom Entity Backend Table (decimal).');
        $connection->createTable($table);

        $table = $schemaSetup->getEntityAttributeValueTable($entityTable, Table::TYPE_TEXT, 255, 'varchar')
            ->setComment('Custom Entity Backend Table (varchar).');
        $connection->createTable($table);

        $table = $schemaSetup->getEntityAttributeValueTable($entityTable, Table::TYPE_TEXT, '64k')
            ->setComment('Custom Entity Backend Table (text).');
        $connection->createTable($table);

        $table = $schemaSetup->getEntityAttributeValueTable($entityTable, Table::TYPE_DATETIME)
            ->setComment('Custom Entity Backend Table (datetime).');
        $connection->createTable($table);

        // Create the custom entity website link table.
        $table = $schemaSetup->getEntityWebsiteTable($entityTable)
            ->setComment('Custom Entity To Website Linkage Table');
        $connection->createTable($table);

        // Fix catalog_eav_attribute table.
        $this->addProductAttributeConfigFields($setup);

        // End setup.
         $setup->endSetup();
    }

    /**
     * Add custom entity attributes special config fields.
     *
     * @param Table $table Base table.
     */
    private function addAttributeConfigFields(Table $table): ?Table
    {
        $table->addColumn(
            'is_global',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '1'],
            'Is Global'
        )
        ->addColumn(
            'is_wysiwyg_enabled',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Is WYSIWYG Enabled'
        )
        ->addColumn(
            'is_html_allowed_on_front',
            Table::TYPE_SMALLINT,
            null,
            ['unsigned' => true, 'nullable' => false, 'default' => '0'],
            'Is HTML Allowed On Front'
        );

        return $table;
    }

    /**
     * Append new configuration field custom_entity_attribute_set_id in catalog_eav_attribute table.
     *
     * @param SchemaSetupInterface $setup Setup
     */
    private function addProductAttributeConfigFields(SchemaSetupInterface $setup): void
    {
        $connection = $setup->getConnection();

        $connection->addColumn(
            $setup->getTable('catalog_eav_attribute'),
            'custom_entity_attribute_set_id',
            [
                'type' => Table::TYPE_SMALLINT,
                'default' => null,
                'unsigned' => true,
                'nullable' => true,
                'comment' => 'Additional swatch attributes data',
            ]
        );

        $connection->addForeignKey(
            $connection->getForeignKeyName(
                'catalog_eav_attribute',
                'custom_entity_attribute_set_id',
                'eav_attribute_set',
                'attribute_set_id'
            ),
            $setup->getTable('catalog_eav_attribute'),
            'custom_entity_attribute_set_id',
            $setup->getTable('eav_attribute_set'),
            'attribute_set_id'
        );
    }
}
