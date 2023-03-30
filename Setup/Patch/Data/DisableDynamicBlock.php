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

use Magento\Banner\Model\BannerFactory;
use Magento\Banner\Model\ResourceModel\Banner\CollectionFactory;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class DisableDynamicBlock
 * @package Kensium\Core\Setup\Patch\Data
 */
class DisableDynamicBlock implements DataPatchInterface
{
    /**
     * @var Config
     */
    private $resourceConfig;
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var CollectionFactory
     */
    private $bannerCollectionFactory;
    /**
     * @var BannerFactory
     */
    private $banner;

    /**
     * IncrementalSomeIntegerPatch constructor.
     * @param Config $resourceConfig
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CollectionFactory $bannerCollectionFactory
     * @param BannerFactory $banner
     */
    public function __construct(
        Config $resourceConfig,
        ModuleDataSetupInterface $moduleDataSetup,
        CollectionFactory $bannerCollectionFactory,
        BannerFactory $banner
    ) {
        $this->resourceConfig = $resourceConfig;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->bannerCollectionFactory = $bannerCollectionFactory;
        $this->banner = $banner;
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
        $bannerCollection = $this->bannerCollectionFactory->create();
        foreach($bannerCollection as $banners){
            $banner=$banners->setIsEnabled(0);
            $banner->save();
        }
    }
        
}
