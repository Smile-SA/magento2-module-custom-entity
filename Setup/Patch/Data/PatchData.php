<?php

declare(strict_types=1);

namespace Smile\CustomEntity\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Smile\CustomEntity\Setup\CustomEntitySetup;
use Smile\CustomEntity\Setup\CustomEntitySetupFactory;

/**
 * Custom entity data setup.
 */
class PatchData implements DataPatchInterface
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
     * @inheritDoc
     */
    public function apply()
    {
        /** @var CustomEntitySetup $customEntitySetup */
        $customEntitySetup = $this->customEntitySetupFactory->create();
        $customEntitySetup->installEntities();
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
