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

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\App\ScopeInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\App\ResourceConnection;

/**
 * Class UpdateConfigurationSettingPatch
 * @package Kensium\Core\Setup\Patch\Data
 */
class UpdateConfigurationSettingPatch implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var Config
     */
    private $resourceConfig;


    /**
     * UpdateConfigurationSettingPatch constructor.
     * @param Config $resourceConfig
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        Config $resourceConfig,
        ModuleDataSetupInterface $moduleDataSetup
    ) {
        $this->resourceConfig = $resourceConfig;
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
     * @return string[]g
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
        $configPath = [
            'contact/contact/enabled',
            'web/cookie/cookie_restriction',
            'catalog/magento_catalogpermissions/grant_catalog_category_view',
            'catalog/review/active',
            'catalog/review/allow_guest',
            'sendfriend/email/enabled',
            'sales/gift_options/allow_order',
            'sales/gift_options/allow_items',
            'sales/gift_options/wrapping_allow_order',
            'sales/gift_options/wrapping_allow_items',
            'multishipping/options/checkout_multiple',
            'magento_giftregistry/general/enabled',
            'catalog/downloadable/shareable',
            'magento_invitation/general/enabled',
            'magento_invitation/general/enabled_on_front',
            'magento_reward/general/is_enabled',
            'magento_reward/general/is_enabled_on_front',
            'fraud_protection/signifyd/active',
            'btob/website_configuration/sharedcatalog_active'
            
        ];
        $this->moduleDataSetup->getConnection()->startSetup();
            foreach ($configPath as $key => $path) {
                $this->resourceConfig->saveConfig(
                    $path,
                    0,
                    ScopeInterface::SCOPE_DEFAULT,
                    0
                );
            }
        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
