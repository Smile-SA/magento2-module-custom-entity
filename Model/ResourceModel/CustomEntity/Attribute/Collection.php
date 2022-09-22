<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute;

use Magento\Eav\Model\Config;
use Magento\Eav\Model\EntityFactory;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Psr\Log\LoggerInterface;
use Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\Collection as SmileCustomEntityCollection;

/**
 * Custom entity attribute collection.
 */
class Collection extends \Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection
{
    private EntityFactory $eavEntityFactory;

    /**
     * Constructor.
     *
     * @param EntityFactoryInterface $entityFactory Entity factory.
     * @param LoggerInterface $logger Logger.
     * @param FetchStrategyInterface $fetchStrategy Fetch strategy
     * @param ManagerInterface $eventManager Event manager.
     * @param Config $eavConfig EAV configuration .
     * @param EntityFactory $eavEntityFactory EAV entity factory
     * @param AdapterInterface|null $connection DB connection.
     * @param AbstractDb|null $resource Resource model.
     */
    public function __construct(
        EntityFactoryInterface $entityFactory,
        LoggerInterface $logger,
        FetchStrategyInterface $fetchStrategy,
        ManagerInterface $eventManager,
        Config $eavConfig,
        EntityFactory $eavEntityFactory,
        ?AdapterInterface $connection = null,
        ?AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $connection, $resource);
        $this->eavEntityFactory = $eavEntityFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setEntityTypeFilter($typeId): self
    {
        return $this;
    }

    /**
     * Method implemented to allow attribute set management through standard blocks.
     * Does nothing. Allow to be compatible with product attribute collection.
     */
    public function addVisibleFilter(): ?SmileCustomEntityCollection
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _construct()
    {
        $this->_init(
            \Smile\CustomEntity\Model\CustomEntity\Attribute::class,
            \Magento\Eav\Model\ResourceModel\Entity\Attribute::class
        );
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.CamelCaseMethodName)
     */
    protected function _initSelect(): self
    {
        $entityTypeId = $this->eavConfig->getEntityType(
            \Smile\CustomEntity\Model\CustomEntity\Attribute::ENTITY_TYPE_CODE
        )->getEntityTypeId();

        $columns = $this->getConnection()->describeTable($this->getResource()->getMainTable());

        $retColumns = [];

        foreach ($columns as $labelColumn => $columnData) {
            $retColumns[$labelColumn] = $labelColumn;
            if ($columnData['DATA_TYPE'] == \Magento\Framework\DB\Ddl\Table::TYPE_TEXT) {
                $retColumns[$labelColumn] = 'main_table.' . $labelColumn;
            }
        }
        $this->getSelect()->from(
            ['main_table' => $this->getResource()->getMainTable()],
            $retColumns
        )->join(
            ['additional_table' => $this->getTable('smile_custom_entity_eav_attribute')],
            'additional_table.attribute_id = main_table.attribute_id'
        )->where(
            'main_table.entity_type_id = ?',
            $entityTypeId
        );

        return $this;
    }
}
