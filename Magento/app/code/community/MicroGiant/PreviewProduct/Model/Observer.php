<?php

/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_PreviewProduct
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * PreviewProduct Observer Model
 * 
 * @category    Magestore
 * @package     Magestore_PreviewProduct
 * @author      Magestore Developer
 */
class MicroGiant_PreviewProduct_Model_Observer {

    /**
     * process controller_action_predispatch event
     *
     * @return MicroGiant_PreviewProduct_Model_Observer
     */
    public function adminhtmlCatalogProductDuplicate($observer) {
        $current_product = $observer->getEvent()->getCurrentProduct();
        $new_product = $observer->getEvent()->getNewProduct();
        if (!$current_product->getPreviewMode()){
            return $this;
        }
        if (!($stockItem = $new_product->getStockItem())) {
            $stockItem = Mage::getModel('cataloginventory/stock_item');
            $stockItem->assignProduct($new_product)
                    ->setData('stock_id', 1)
                    ->setData('store_id', 1);
            $stockItem->setData('manage_stock', 0);
            $stockItem->setData('use_config_manage_stock', 0);
            $stockItem->setData('use_config_min_sale_qty', 1);
            $stockItem->setData('use_config_max_sale_qty', 1);
        }
//        $new_product->setData('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED);
//        $new_product->setIsPreviewMode(true);
//        $new_product->setSku('microgiant-product-preview-'.$current_product->getTypeId());
        $new_product->getStockItem();
        return $this;
    }
    public function catalogProductView($observer){
        $product = $observer['product'];
        if($product->getSku() == 'microgiant-preview-'.$product->getId()){
            Mage::getModel('catalog/product')->setData('status', Mage_Catalog_Model_Product_Status::STATUS_DISABLED)
                    ->setId($product->getId())
                    ->save();
        }
        return $this;
    }
    public function controllerActionPredispatch($observer){
        $action = $observer->getEvent()->getControllerAction();
        $productID = $action->getRequest()->getParam('id');
        $product = Mage::getModel('catalog/product')->load($productID);
        if(!$product->getId()) return $this;
        if($product->getSku() == 'microgiant-preview-'.$product->getId()){
            Mage::getModel('catalog/product')->setData('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
                    ->setId($product->getId())
                    ->save();
        }
        return $this;
    }

}
