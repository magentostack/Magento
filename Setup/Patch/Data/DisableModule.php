<?php
/**
 * Kensium_Core
 *
 * @category: PHP
 * @package: Kensium/Core
 * @copyright: Copyright © 2019 Magento. All rights reserved.
 * See COPYING.txt for license details.
 * @license: Magento Enterprise Edition (MEE) license
 * @author: Dharmendra.Kothe <dharmendrak@kensium.com>
 * @project: VP Supply
 * @keywords:  Admin Custom Menu
 */

declare(strict_types=1);

namespace Kensium\Core\Setup\Patch\Data;

use Magento\Framework\Module\StatusFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class DisableModule.php
 * @package Kensium\Core\Setup\Patch\Data
 */
class DisableModule implements DataPatchInterface
{
    /**
     * @var Magento\Framework\Module\StatusFactory
     */
    private $statusFactory;
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * UpdateConfigurationSettingPatch constructor.
     * @param StatusFactory $statusFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        StatusFactory $statusFactory,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->statusFactory = $statusFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @return string
     */
    public static function getVersion()
    {
        return '2.0.0';
    }

    /**
     * Get array of patches that have to be executed prior to this.
     *
     * Example of implementation:
     *
     * [
     *      \Vendor_Name\Module_Name\Setup\Patch\Patch1::class,
     *      \Vendor_Name\Module_Name\Setup\Patch\Patch2::class
     * ]
     *
     * @return string[]
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * Get aliases (previous names) for the patch.
     *
     * @return string[]
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * Run code inside patch
     * If code fails, patch must be reverted, in case when we are speaking about schema - then under revert
     * means run PatchInterface::revert()
     *
     * If we speak about data, under revert means: $transaction->rollback()
     *
     * @return void
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $moduleStatusFactory = $this->statusFactory->create();
        $moduleStatusFactory->setIsEnabled(
            true,
            [
                'WeltPixel_LayeredNavigation'
            ]
        );
        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
