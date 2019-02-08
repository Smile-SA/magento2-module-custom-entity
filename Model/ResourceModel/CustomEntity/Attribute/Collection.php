<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\CustomEntity
 * @author    Aurelien FOUCRET <aurelien.foucret@smile.fr>
 * @copyright 2019 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */

namespace Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute;

/**
 * Custom entity attribute collection.
 *
 * @category Smile
 * @package  Smile\CustomEntity
 * @author   Aurelien FOUCRET <aurelien.foucret@smile.fr>
 */
class Collection extends \Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection
{
    /**
     * @var \Magento\Eav\Model\EntityFactory
     */
    private $eavEntityFactory;

    /**
     * Constructor.
     *
     * @param \Magento\Framework\Data\Collection\EntityFactoryInterface    $entityFactory    Entity factory.
     * @param \Psr\Log\LoggerInterface                                     $logger           Logger.
     * @param \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy    Fetch strategy
     * @param \Magento\Framework\Event\ManagerInterface                    $eventManager     Event manager.
     * @param \Magento\Eav\Model\Config                                    $eavConfig        EAV configuration .
     * @param \Magento\Eav\Model\EntityFactory                             $eavEntityFactory EAV entity factory
     * @param \Magento\Framework\DB\Adapter\AdapterInterface|null          $connection       DB connection.
     * @param \Magento\Framework\Model\ResourceModel\Db\AbstractDb|null    $resource         Resource model.
     */
    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Eav\Model\EntityFactory $eavEntityFactory,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $eavConfig, $connection, $resource);
        $this->eavEntityFactory = $eavEntityFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function setEntityTypeFilter($typeId)
    {
        return $this;
    }

    /**
     * Method implemented to allow attribute set management through standard blocks.
     * Does nothing. Allow to be compatible with product attribute collection.
     *
     * @return \Smile\CustomEntity\Model\ResourceModel\CustomEntity\Attribute\Collection
     */
    public function addVisibleFilter()
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
    protected function _initSelect()
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
