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

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory;
use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
/**
 * Class DisableTypeProduct
 * @package Kensium\Core\Setup\Patch\Data
 */
class DisableTypeProduct implements DataPatchInterface
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
     * @var CollectionFactory
     */
    private $productCollectionFactory;
    /**
     * @var ProductFactory
     */
    private $product;
    /**
     * @var State
     */
    private $state;

    /**
     * IncrementalSomeIntegerPatch constructor.
     * @param Config $resourceConfig
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CollectionFactory $productCollectionFactory
     * @param ProductFactory $product
     * @param State $state
     */
    public function __construct(
        Config $resourceConfig,
        ModuleDataSetupInterface $moduleDataSetup,
        CollectionFactory $productCollectionFactory,
        ProductFactory $product,
        State $state
    ) {
        $this->resourceConfig = $resourceConfig;
        $this->moduleDataSetup = $moduleDataSetup;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->product = $product;
        $this->state = $state;
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
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function apply()
    {
        $this->state->setAreaCode(Area::AREA_FRONTEND);
        $productCollection = $this->productCollectionFactory->create()
                        ->addAttributeToFilter('type_id',array('configurable','virtual','downloadable'))
                        ->addAttributeToSelect('*');

        foreach ($productCollection as $products) { 
            $product = $this->product->create()->load($products->getEntityId());
            $product->setStatus(0);
            $product->save();
        }
    }
}
