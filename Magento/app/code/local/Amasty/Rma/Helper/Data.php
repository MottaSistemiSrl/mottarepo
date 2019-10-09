<?php
/**
 * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
 */ 
class Amasty_Rma_Helper_Data extends Mage_Core_Helper_Abstract
{
    public static $STATUS_ACTIVE = 1;
    public static $STATUS_INACTIVE = 0;
    
    public function getStatuses()
    {
        return array(
                self::$STATUS_ACTIVE => Mage::helper('amrma')->__('Active'),
                self::$STATUS_INACTIVE => Mage::helper('amrma')->__('Inactive')
            );       
    }
    
    public function getLoginUrl()
    {
        return $this->_getUrl('amrma/guest/login');
    }
    
    public function getLoginPostUrl()
    {
        $params = array();
        
        return $this->_getUrl('amrma/guest/loginPost', $params);
    }
    
    public function getLogoutUrl()
    {
        return $this->_getUrl('amrma/guest/logout');
    }
    
    public function getOrderItems($orderId){
        
        if ($orderId instanceof Mage_Sales_Model_Order) {
            $orderId = $orderId->getId();
        }
        return Mage::getModel('amrma/item')->getOrderItems($orderId);
    }
    
    protected function _getArrayFromConfig($key, $store = NULL){
        $ret = array();
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        $val = Mage::getStoreConfig($key);
        if (!is_array($val)){
            $arr = unserialize ($val);
            
            foreach($arr as $el){
                $ret[] = $el['value'];
            }
        }
        else {
            $ret = $val;
        }
        
        return $ret;
    }


    public function getResolutions($store = NULL){
        return $this->_getArrayFromConfig('amrma/properties/resolutions', $store);
    }
    
    public function getConditions($store = NULL){
        return $this->_getArrayFromConfig('amrma/properties/conditions', $store);
    }
    
    public function getReasons($store = NULL){
        return $this->_getArrayFromConfig('amrma/properties/reasons', $store);
    }
    
    public function getRequestStatuses(){
        $ret = array();
        
        $collection = Mage::getModel('amrma/status')
                ->getCollection()
                ->addLabel()
                ->sortByOrder();
        $collection->addFilter('is_active', 1);
        foreach($collection as $status){
            $ret[$status->getId()] = $status->getLabel();
        }
        return $ret;
    }
    
    public function getBooleanOptions(){
        return array(
            '' => $this->__(''),
            '1' => $this->__('Yes'),
            '0' => $this->__('No'),
        );
    }
    
    public function getFailReason($orderId){
        $ret = NULL;
        $order = Mage::getModel("sales/order")->load($orderId);
        
        if ($order->getId()){
            
            $allowedDays = Mage::helper("amrma")->getAllowedDays();
            
            if (!empty($allowedDays)){
                $date = Mage::getSingleton('core/date');

                $t = ($date->timestamp() - (60*60*24*$allowedDays));

                if ($date->gmtTimestamp($order->getCreatedAt()) > $t){
                    $ret = "You can not create RMA for this order because it is " . $allowedDays . " days since purchase";
                }
            }
        }
        
        if (!$ret)
            $ret = "You can not create RMA for this order";
        
        return $ret;
    }
    
    public function canCreateRma($orderId)
    {
        $items = Mage::getModel("amrma/item")->getOrderItems($orderId);
        if ($items->count()) {
            return true;
        }

        return false;
    }
    
    public function getRequestsCount($orderId){
        return Mage::getModel('amrma/request')->getCollection()
            ->addFilter("order_id", $orderId)
            ->getSize();
    }
    
    public function getShippingConfirmation($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/shipping/confirmation', $store);
    }
    
    public function isModuleEnabled($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/general/enabled', $store) == 1;
    }
    
    public function isGuestAllow($store = NULL){
        
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        
        return $this->isModuleEnabled($store) &&
                Mage::getStoreConfig('amrma/general/guest', $store) == 1;
    }
    
    public function getReturnAddress($store = NULL){
        $ret = null;
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        
        $useDefault = Mage::getStoreConfig('amrma/shipping/default', $store) == 1;
        
        if ($useDefault){
            $address = array();
            
            $country = Mage::getStoreConfig('shipping/origin/country_id', $store);
            $state = Mage::getStoreConfig('shipping/origin/region_id', $store);
            $postcode = Mage::getStoreConfig('shipping/origin/postcode', $store);
            $city = Mage::getStoreConfig('shipping/origin/city', $store);
            $street_line1 = Mage::getStoreConfig('shipping/origin/street_line1', $store);
            $street_line2 = Mage::getStoreConfig('shipping/origin/street_line2', $store);
            
            if ($country)
                $address[] = Mage::app()->getLocale()->getCountryTranslation($country);
            
            if ($state){
                if (is_numeric($state)){
                    $region = Mage::getModel('directory/region')->load($state);
                    $address[] = $region->getName();
                } else {
                    $address[] = $state;
                }
            }
            
            if ($postcode)
                $address[] = $postcode;
            
            if ($city)
                $address[] = $city;
            
            if ($street_line1)
                $address[] = $street_line1;
            
            if ($street_line2)
                $address[] = $street_line2;

            $ret = implode("<br/>", $address);
            
        } else {
            $ret = nl2br(Mage::getStoreConfig('amrma/shipping/address', $store));
        }
        return $ret;
    }
    
    public function getIsEnablePerItem($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/general/enable_per_item', $store) == 1;
    }
    
    public function getIsGuestEnabled($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/general/guest', $store) == 1;
    }
    
    public function getIsNotifyCustomer($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/email/notify_customer', $store) == 1;
    }
    
    public function getIsNotifyAdmin($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/email/notify_admin', $store) == 1;
    }
    
    public function getIsAllowPrintLabel($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/general/print_label', $store) == 1;
    }
    
    public function getAllowedDays($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/general/days', $store);
    }
    
    public function getMaxAttachmentSize($store = NULL){
        if (!$store) {
            $store = Mage::app()->getStore();
        }
        return Mage::getStoreConfig('amrma/email/max', $store);
    }
    
    public function getEmailTemplatesOptions(){
        $collection = Mage::getResourceModel('core/email_template_collection')
                ->addFilter("orig_template_code", "amrma_status")
                ->load();
        
        $options = $collection->toOptionArray();
        return $options;
    }
}