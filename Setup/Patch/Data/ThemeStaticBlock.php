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

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Cms\Model\PageFactory;
use Magento\Cms\Model\BlockFactory;

/**
 * Class ThemeStaticBlock
 * @package Kensium\Core\Setup\Patch\Data
 */
class ThemeStaticBlock implements DataPatchInterface
{

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var PageFactory
     */
    private $pageFactory;
    /**
     * @var BlockFactory
     */
    private $blockFactory;

    /**
     * UpdateConfigurationSettingPatch constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockFactory $blockFactory
     * @param PageFactory $pageFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockFactory $blockFactory,
        PageFactory $pageFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
        $this->pageFactory = $pageFactory;
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
        $this->globalPromoBlock();
        $this->footerBlock();
        $this->homepageCustom();
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    /**
     * Create page
     *
     * @return \Magento\Cms\Model\Page
     */
    public function createPage()
    {
        return $this->pageFactory->create();
    }

    /**
     * Create block
     *
     * @return Page
     */
    public function createBlock()
    {
        return $this->blockFactory->create();
    }

    /**
     * Site Promotion Static Block
     */
    public function globalPromoBlock()
    {
        $cmsBlockContent = <<<EOD
            <div id="carousel">
                <div class="btn-bar">
                    <div id="buttons">
                        <a id="prev" class="icon-angle-left" href="#">&nbsp;</a>
                        <a id="next" class="icon-angle-right" href="#">&nbsp;</a>
                    </div>
                </div>
                <div id="slides">
                    <ul>
                        <li class="slide">
                            <div class="quoteContainer">
                                <span class="wpx-sub">SIGN UP FOR OUR DISTRIBUTOR NEWSLETTER - VP BLAST</span>
                            </div>
                        </li>
                        <li class="slide">
                            <div class="quoteContainer">
                                <span class="wpx-sub">SIGN UP FOR OUR DISTRIBUTOR NEWSLETTER - VP BLAST</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
EOD;

            $cmsBlock = $this->createBlock()->load('weltpixel_global_promo_message', 'identifier');
            if (!$cmsBlock->getId()) {
                $cmsBlock = [
                    'title' => 'WeltPixel Global Promo Message',
                    'identifier' => 'weltpixel_global_promo_message',
                    'content' => $cmsBlockContent,
                    'is_active' => 1,
                    'stores' => [0],
                ];
                $this->createBlock()->setData($cmsBlock)->save();
            } else {
                $cmsBlock->setContent($cmsBlockContent)->save();
            }
    }

    /**
     * Theme static footer block
     */
    public function footerBlock()
    {
        $cmsBlockContent = <<<EOD
<div class="footer-v1">
   <div class="footer-v1-content">
        <div class="col-lg-12 col-sm-12 col-xs-12 newsletter-subscribe">
            <form action="{{store url="newsletter/subscriber/new/"}}" method="post"
                  id="newsletter-footer" data-mage-init='{"validation": {"errorClass": "mage-error"}}'>
                <div class="form-group">
                    <label>SIGN UP FOR OUR NEWSLETTER</label>
                    <div class="email-col">
                        <input name="email" type="email" id="newsletter-bottom" placeholder="Enter your email address"
                           data-validate="{required:true, 'validate-email':true}"
                           class="input-text required-entry validate-email"/>
                    </div>
                    <button class="button" title="Subscribe" type="submit">
                        <span>Sign Up</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="footer-lg-desktop">
          <div class="col-lg-12 col-sm-12 col-xs-12 footer-col-link">
              <div class="col-lg-5 col-sm-5 col-xs-6 nopaddingleft">
                  <p class="footer-title">Products</p>
                  <div class="col-lg-6 col-sm-6 col-xs-12 nopaddingleft">
                      <ul class="footer links">
                          <li class="nav item"><a href="#" target="_blank"><span>Plumbing</span></a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Commercial</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Residential Bath</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Contractor Support</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Product Lines</a></li>
                      </ul>
                      <ul class="footer links">
                          <li class="nav item"><a href="#" target="_blank"><span>HVAC</span></a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Commercial</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Residential</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">HVAC Training</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Product Lines</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Dealer Development</a></li>
                      </ul>
                  </div>
                  <div class="col-lg-6 col-sm-6 col-xs-12 nopaddingleft">
                      <ul class="footer links">
                          <li class="nav item"><a href="#" target="_blank"><span>Kitchens</span></a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Commercial</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Residential</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Product Lines</a></li>
                      </ul>
                      <ul class="footer links">
                          <li class="nav item"><a href="#" target="_blank"><span>Fastners & Safety</span></a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Fastners</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Safety</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Product Lines</a></li>
                      </ul>
                      <ul class="footer links">
                          <li class="nav item"><a href="#" target="_blank"><span>Renewable Energy</span></a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Geothermal</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Solar Photovoltaic</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Solar Thermal</a></li>
                          <li class="nav item"><a href="#" target="_blank" class="leftpadding">Product Lines</a></li>
                      </ul>
                  </div>
              </div>
              <div class="col-lg-3 col-sm-3 col-xs-6 nopaddingleft">
                  <p class="footer-title">About</p>
                  <ul class="footer links">
                      <li class="nav item"><a href="#" target="_blank"><span>About VP Supply</span></a></li>
                      <li class="nav item"><a href="#" target="_blank" class="leftpadding">Product Lines</a></li>
                      <li class="nav item"><a href="#" target="_blank" class="leftpadding">Our Staff</a></li>
                      <li class="nav item"><a href="#" target="_blank" class="leftpadding">MVP Program</a></li>
                      <li class="nav item"><a href="#" target="_blank" class="leftpadding">VP Events</a></li>
                      <li class="nav item"><a href="#" target="_blank" class="leftpadding">Locations</a></li>
                      <li class="nav item"><a href="#" target="_blank" class="leftpadding">Contact Us</a></li>
                      <li class="nav item"><a href="#" target="_blank" class="leftpadding">Employment Opportunities</a></li>
                      <li class="nav item"><a href="#" target="_blank" class="leftpadding">Site Map</a></li>
                      <li class="nav item"><a href="#" target="_blank"><span>Credit Application</span></a></li>
                  </ul>
              </div>
              <div class="col-lg-4 col-sm-4 col-xs-12 nopaddingleft">
                  <h4 class="mini-logo">
                      <a href="{{store url=""}}">
                          <img alt="Logo" src="{{view url="images/logo.png"}}">
                      </a>
                  </h4>
                  <div class="pull-right-md social-icons-v1">
                      <a href="#" class="social-icons si-borderless si-twitter">
                          <i class="icon-twitter"></i>
                          <i class="icon-twitter"></i>
                      </a>
                      <a href="#" class="social-icons si-borderless si-facebook">
                          <i class="icon-facebook"></i>
                          <i class="icon-facebook"></i>
                      </a>
                      <a href="#" class="social-icons si-borderless si-flickr">
                          <i class="icon-flickr"></i>
                          <i class="icon-flickr"></i>
                      </a>
                       <a href="#" class="social-icons si-borderless si-youtube">
                          <i class="icon-youtube"></i>
                          <i class="icon-youtube"></i>
                      </a>
                      <a href="#" class="social-icons si-borderless si-linkedin">
                          <i class="icon-linkedin"></i>
                          <i class="icon-linkedin"></i>
                      </a>
                  </div>
              </div>
          </div>
        </div>
    </div>
</div>
EOD;

        $cmsBlock = $this->createBlock()->load('weltpixel_footer_v1', 'identifier');
        if (!$cmsBlock->getId()) {
            $cmsBlock = [
                'title' => 'WeltPixel Footer V1',
                'identifier' => 'weltpixel_footer_v1',
                'content' => $cmsBlockContent,
                'is_active' => 1,
                'stores' => [0],
            ];
            $this->createBlock()->setData($cmsBlock)->save();
        } else {
            $cmsBlock->setContent($cmsBlockContent)->save();
        }
    }

    /**
     * Theme static homepage custom block
     */
    public function homepageCustom()
    {
      $pageContent = <<<EOD
{{widget type="WeltPixel\OwlCarouselSlider\Block\Slider\Custom" slider_id="11"}}
{{widget type="Magento\Cms\Block\Widget\Block" template="widget/static_block/default.phtml" block_id="39"}}
{{widget type="Magento\Cms\Block\Widget\Block" template="widget/static_block/default.phtml" block_id="40"}}
{{widget type="Magento\Cms\Block\Widget\Block" template="widget/static_block/default.phtml" block_id="41"}}
EOD;
      $cmsPage = $this->createPage()->load('vp-supply-homepage', 'identifier');

      if (!$cmsPage->getId()) {
        $cmsPageContent = [
          'title' => 'VP Supply - Homepage',
          'page_layout' => 'cms-full-width',
          'identifier' => 'vp-supply-homepage',
          'content' => $pageContent,
          'is_active' => 1,
          'stores' => [0],
          'sort_order' => 0
        ];
        $this->createPage()->setData($cmsPageContent)->save();
      } else {
        $cmsPage->setContent($pageContent)->save();
      }

      $cmsBlockContent1 = <<<EOD
<div class="homepage-category-section">
  <h1 class="title-homepage">Product Categories</h1>
  <div class="homepage-category-content">
    <div class="col-lg-12 col-sm-12 col-xs-12 category-grid-homepage">
      <div class="cat-list">
        <div class="col-lg-12"><a href="#"> <img src="{{view url="images/homepage/product-category.png"}}"> <span class="category-title">Plumbing</span> </a></div>
      </div>
      <div class="cat-list">
        <div class="col-lg-12"><a href="#"> <img src="{{view url="images/homepage/product-category.png"}}"> <span class="category-title">HVAC</span> </a></div>
      </div>
      <div class="cat-list">
        <div class="col-lg-12"><a href="#"> <img src="{{view url="images/homepage/product-category.png"}}"> <span class="category-title">Kitchens</span> </a></div>
      </div>
      <div class="cat-list">
        <div class="col-lg-12"><a href="#"> <img src="{{view url="images/homepage/product-category.png"}}"> <span class="category-title">Fastners</span> </a></div>
      </div>
      <div class="cat-list">
        <div class="col-lg-12"><a href="#"> <img src="{{view url="images/homepage/product-category.png"}}"> <span class="category-title">Safety</span> </a></div>
      </div>
      <div class="cat-list">
        <div class="col-lg-12"><a href="#"> <img src="{{view url="images/homepage/product-category.png"}}"> <span class="category-title">Solar</span> </a></div>
      </div>
      <div class="cat-list">
        <div class="col-lg-12"><a href="#"> <img src="{{view url="images/homepage/product-category.png"}}"> <span class="category-title">Geothermal</span> </a></div>
      </div>
      <div class="cat-list">
        <div class="col-lg-12"><a href="#"> <img src="{{view url="images/homepage/product-category.png"}}"> <span class="category-title">All Product Lines</span> </a></div>
      </div>
    </div>
  </div>
</div>
EOD;

      $cmsBlock1 = $this->createBlock()->load('vpsupply-homepage-product-category', 'identifier');
      if (!$cmsBlock1->getId()) {
          $cmsBlock1 = [
              'title' => 'VP Supply - Homepage Product Category',
              'identifier' => 'vpsupply-homepage-product-category',
              'content' => $cmsBlockContent1,
              'is_active' => 1,
              'stores' => [0],
          ];
          $this->createBlock()->setData($cmsBlock1)->save();
      } else {
          $cmsBlock1->setContent($cmsBlockContent1)->save();
      }

      $cmsBlockContent2 = <<<EOD
<div class="homepage-featured-news-section">
  <div class="homepage-featured-news-content">
    <div class="col-lg-12 col-sm-12 col-xs-12 featured-homepage">
      <div class="col-lg-12 col-sm-12 col-xs-12 left-image">
        <div class="col-lg-6 col-sm-6 col-xs-12 img-col"><img src="{{view url="images/homepage/featured-news.jpg"}}"></div>
        <div class="col-lg-6 col-sm-6 col-xs-12 desc-col">
          <div class="desc">
            <div class="title">It's the Giving Season!</div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.</p>
            <a class="btn btn-primary" href="#">Read More</a></div>
          </div>
        </div>
        <div class="col-lg-12 col-sm-12 col-xs-12 right-image">
          <div class="col-lg-6 col-sm-6 col-xs-12 desc-col">
            <div class="desc">
              <div class="title">The Future of Kitchens</div>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, vulputate eu pharetra nec, mattis ac neque. Duis vulputate commodo lectus, ac blandit elit tincidunt id.</p>
              <a class="btn btn-primary" href="#">Read More</a></div>
            </div>
            <div class="col-lg-6 col-sm-6 col-xs-12 img-col"><img src="{{view url="images/homepage/featured-news.jpg"}}"></div>
          </div>
        </div>
      </div>
</div>
EOD;

      $cmsBlock2 = $this->createBlock()->load('vpsupply-homepage-featured-news', 'identifier');
      if (!$cmsBlock2->getId()) {
          $cmsBlock2 = [
              'title' => 'VP Supply - Homepage Featured News',
              'identifier' => 'vpsupply-homepage-featured-news',
              'content' => $cmsBlockContent2,
              'is_active' => 1,
              'stores' => [0],
          ];
          $this->createBlock()->setData($cmsBlock2)->save();
      } else {
          $cmsBlock2->setContent($cmsBlockContent2)->save();
      }

      $cmsBlockContent3 = <<<EOD
<div class="homepage-news-section">
  <h1 class="title-homepage">VP News Blast</h1>
  <div class="homepage-news-content">
    <div class="col-lg-12 col-sm-12 col-xs-12 news-grid-homepage">
      <div class="col-lg-4 col-sm-4 col-xs-12 news-list"><a href="#"> <img src="{{view url="images/homepage/news.png"}}"> </a>
        <h3 class="news-title">Article title goes here</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, oncat veladium vulputate eu pharetra nec</p>
        <a class="btn btn-primary" href="#">Read More</a></div>
      <div class="col-lg-4 col-sm-4 col-xs-12 news-list"><a href="#"> <img src="{{view url="images/homepage/news.png"}}"> </a>
        <h3 class="news-title">Article title goes here</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, oncat veladium vulputate eu pharetra nec</p>
        <a class="btn btn-primary" href="#">Read More</a></div>
      <div class="col-lg-4 col-sm-4 col-xs-12 news-list"><a href="#"> <img src="{{view url="images/homepage/news.png"}}"> </a>
        <h3 class="news-title">Article title goes here</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla quam velit, oncat veladium vulputate eu pharetra nec</p>
        <a class="btn btn-primary" href="#">Read More</a></div>
    </div>
  </div>
</div>
EOD;

      $cmsBlock3 = $this->createBlock()->load('vpsupply-homepage-news-blast', 'identifier');
      if (!$cmsBlock3->getId()) {
          $cmsBlock3 = [
              'title' => 'VP Supply - Homepage News Blast',
              'identifier' => 'vpsupply-homepage-news-blast',
              'content' => $cmsBlockContent3,
              'is_active' => 1,
              'stores' => [0],
          ];
          $this->createBlock()->setData($cmsBlock3)->save();
      } else {
          $cmsBlock3->setContent($cmsBlockContent3)->save();
      }

    }
}
