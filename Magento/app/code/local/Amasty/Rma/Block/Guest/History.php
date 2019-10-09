<?php
/**
 * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
 */ 
class Amasty_Rma_Block_Guest_History extends Mage_Core_Block_Template
{
    public static $_MODE_CUSTOMER = 'customer';
    public static $_MODE_GUEST = 'guest';
    protected $_rma;
    
    public function __construct()
    {
        parent::__construct();
        $this->setCollection(self::$_MODE_GUEST);
    }
    
    protected function _getSession(){
        return Mage::getSingleton('amrma/session');
    }
    
    
    public function setCollection($mode){
        $requests = Mage::getResourceModel('amrma/request_collection')
            ->addFieldToSelect('*')
            ->setOrder('created', 'desc')
        ;
        
        $this->setMode($mode);
        
        $this->_addFilter($requests, 'main_table.customer_id', 'main_table.email');

        $this->setRequests($requests);
    }
    
    protected function _getCustomerEmail(){
        $ret = NULL;
        
        if ($this->getMode() == self::$_MODE_CUSTOMER){
            $ret = Mage::getSingleton('customer/session')->getCustomer()->getEmail();
        } else if ($this->getMode() == self::$_MODE_GUEST){
            $salesOrder = Mage::getModel("sales/order")->load($this->_getSession()->getId());
            $ret = $salesOrder->getCustomerEmail();
        }

        return $ret;
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'amrma.customer.order.pager')
            ->setCollection($this->getRequests());
        $this->setChild('pager', $pager);
        $this->getRequests()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
        
    public function getLogoutUrl(){
        return Mage::helper('amrma')->getLogoutUrl();
    }
    
    public function getViewUrl($request)
    {
        return $this->getUrl('*/*/view', array('id' => $request->getId()));
    }
    
    public function getNewUrl($orderId)
    {
        return $this->getUrl('*/*/new', array('order_id' => $orderId));
    }
    
    public function getDeleteUrl($request)
    {
        return $this->getUrl('*/*/delete', array('id' => $request->getId()));
    }
    
    protected function _addFilter($collection, $customerFilter = 'customer_id', $guestFilter = 'customer_email'){
        if ($this->getMode() == self::$_MODE_CUSTOMER){       
            $collection->addFieldToFilter($customerFilter, Mage::getSingleton('customer/session')->getCustomerId());		
        } 
        else { // todo refactor
            $collection->addFieldToFilter($guestFilter, $this->_getCustomerEmail());
        }
    }
    
    public function getAvailableOrders()
    {
        $ret = array();
        
        $collection = Mage::getModel('sales/order_item')->getCollection();
        
        $collection->getSelect()
                ->joinLeft(
                    array('order' => $collection->getTable('sales/order')), 
                    'order.entity_id = main_table.order_id', 
                    array('increment_id', 'created_at as order_created_at', 'grand_total as order_grand_total')
                );
                
        $collection->addFieldToFilter('qty_shipped', array("gt" => 0));
        
        $this->_addFilter($collection);
        
        $collection->addOrder('entity_id', 'desc');
                
        $tpl = Mage::helper('amrma')->__('Order #%s - %s - %s');
        foreach($collection as $item){
            $ret[$item->getOrderId()] = sprintf($tpl, 
		$item->getIncrementId(), 
		Mage::helper('core')->formatDate($item->getOrderCreatedAt()),
                Mage::helper('core')->formatPrice($item->getOrderGrandTotal())
            );
        }
        
        return $ret;
    }
}
?>