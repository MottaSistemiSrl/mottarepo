<?php

class Sevenlike_Mediaserver_Block_Customer_Products_List extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $session = Mage::getSingleton('customer/session');
		
		$_customer = Mage::getSingleton('customer/session')->getCustomer();
		if (!@$GLOBALS["_sl_bought_video"]) {
			$orders = Mage::getResourceModel('sales/order_collection')
				->addFieldToSelect('*')
				->addFieldToFilter('customer_id', $_customer->getId())
				->addFieldToFilter('state', "complete")
				->addAttributeToSort('created_at', 'DESC');
				
			$products = array();
			foreach ($orders as $order) {
				foreach ($order->getAllItems() as $item) {
					if (!$item->getIsVirtual()) continue;
					$product = $item->getProduct();
					if ($product->getAttributeSetId() != 11) continue;
					$products[$product->getId()] = $product;
				}
			}
			
			$GLOBALS["_sl_bought_video"] = $products;
		}
		
        $this->setItems($GLOBALS["_sl_bought_video"]);
    }

    public function getBackUrl()
    {
        if ($this->getRefererUrl()) {
            return $this->getRefererUrl();
        }
        return $this->getUrl('customer/account/');
    }
}
