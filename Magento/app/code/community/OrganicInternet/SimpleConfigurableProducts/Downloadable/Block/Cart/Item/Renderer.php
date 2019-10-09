<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Downloadable
 * @copyright   Copyright (c) 2013 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Shopping cart downloadable item render block
 *
 * @category    Mage
 * @package     Mage_Downloadable
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class OrganicInternet_SimpleConfigurableProducts_Downloadable_Block_Cart_Item_Renderer extends Mage_Checkout_Block_Cart_Item_Renderer
{

    /**
     * Retrieves item links options
     *
     * @return array
     */
    public function getLinks()
    {
        return Mage::helper('downloadable/catalog_product_configuration')->getLinks($this->getItem());
    }

    /**
     * Return title of links section
     *
     * @return string
     */
    public function getLinksTitle()
    {
        return Mage::helper('downloadable/catalog_product_configuration')->getLinksTitle($this->getProduct());
    }
    protected function getConfigurableProductParentId()
    {
        if ($this->getItem()->getBuyRequest()->getCpid()) {
            return $this->getItem()->getBuyRequest()->getCpid();
        }
        return null;
    }

    protected function getConfigurableProductParent()
    {
        return Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($this->getConfigurableProductParentId());
    }

    public function getProduct()
    {
        return Mage::getModel('catalog/product')
            ->setStoreId(Mage::app()->getStore()->getId())
            ->load($this->getItem()->getProductId());
    }

    public function getProductName()
    {
        if (Mage::getStoreConfig('SCP_options/cart/show_configurable_product_name')
            && $this->getConfigurableProductParentId()) {
            return $this->getConfigurableProductParent()->getName();
        } else {
            return parent::getProductName();
        }
    }


    /* Bit of a hack this - assumes configurable parent is always linkable */
    public function hasProductUrl()
    {
        if ($this->getConfigurableProductParentId()) {
            return true;
        } else {
            return parent::hasProductUrl();
        }
    }

    public function getProductUrl()
    {
        if ($this->getConfigurableProductParentId()) {
            return $this->getConfigurableProductParent()->getProductUrl();
        } else {
            return parent::getProductUrl();
            #return $this->getProduct()->getProductUrl();
        }
    }

    public function getOptionList()
    {
        $options = false;
        if (Mage::getStoreConfig('SCP_options/cart/show_custom_options')) {
            $options = parent::getOptionList();
        }

        if (Mage::getStoreConfig('SCP_options/cart/show_config_product_options')) {
            if ($this->getConfigurableProductParentId()) {
                $attributes = $this->getConfigurableProductParent()
                    ->getTypeInstance()
                    ->getUsedProductAttributes();
                foreach($attributes as $attribute) {
                    $options[] = array(
                        'label' => $attribute->getFrontendLabel(),
                        'value' => $this->getProduct()->getAttributeText($attribute->getAttributeCode()),
                        'option_id' => $attribute->getId(),
                    );
                }
            }
        }
        return $options;
    }

    /*
    Logic is:
    If not SCP product, use normal thumbnail behaviour
    If is SCP product, and admin value is set to use configurable image, do so
    If is SCP product, and admin value is set to use simple image, do so,
      but 'fail back' to configurable image if simple image is placeholder
    If logic says to use it, but configurable product image is placeholder, then
      just display placeholder

    */
    public function getProductThumbnail()
    {
        #If product not added via SCP, use default behaviour
        if (!$this->getConfigurableProductParentId()) {
            return parent::getProductThumbnail();
        }


        #If showing simple product image
        if (!Mage::getStoreConfig('SCP_options/cart/show_configurable_product_image')) {
            $product = $this->getProduct();
            #if product image is not a thumbnail
            if($product->getData('thumbnail') && ($product->getData('thumbnail') != 'no_selection')) {
                return $this->helper('catalog/image')->init($product, 'thumbnail');
            }
        }

        #If simple prod thumbnail image is placeholder, or we're not using simple product image
        #show configurable product image
        $product = $this->getConfigurableProductParent();
        return $this->helper('catalog/image')->init($product, 'thumbnail');
    }
}
