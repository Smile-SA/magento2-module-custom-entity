<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Setup\Patch\Data;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Custom entity data setup.
 */
class PatchData implements InstallDataInterface
{
    /**
     * Custom entity EAV setup factory
     */
    private CustomEntitySetupFactory $customEntitySetupFactory;

    /**
     * Constructor.
     *
     * @param CustomEntitySetupFactory $customEntitySetupFactory Custom entity EAV setup factory.
     */
    public function __construct(CustomEntitySetupFactory $customEntitySetupFactory)
    {
        $this->customEntitySetupFactory = $customEntitySetupFactory;
    }

    /**
     * @inheritdoc
     */
    // @codingStandardsIgnoreLine Context param not used (Generic.CodeAnalysis.UnusedFunctionParameter.Found)
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomEntitySetup $customEntitySetup */
        $customEntitySetup = $this->customEntitySetupFactory->create(['setup' => $setup]);
        $customEntitySetup->installEntities();
    }
}
