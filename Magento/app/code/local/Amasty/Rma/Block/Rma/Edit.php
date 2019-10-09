<?php
    /**
    * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
    */ 
    class Amasty_Rma_Block_Rma_Edit extends Mage_Core_Block_Template
    {
        protected $_order;
        protected function _prepareLayout()
        {
            $hlr = Mage::helper('amrma');
            
            parent::_prepareLayout();
            $this->_order = Mage::getModel('sales/order');

            if ($id = $this->getRequest()->getParam('order_id')) {
                $this->_order->load($id);    
                Mage::register('current_order', $this->_order);
            }
            
            $this->setItems($hlr->getOrderItems($this->_order));
            $this->setConditions($hlr->getConditions());
            $this->setResolutions($hlr->getResolutions());
            $this->setReasons($hlr->getReasons());
        }
        
        public function getOrder(){
            return $this->_order;
        }
        
        public function getUploadUrl()
        {
            return $this->getUrl('*/*/upload');
        }
        
        public function getBackUrl()
        {
            return Mage::getUrl('*/*/history');
        }
        
        public function getIsEnablePerItem(){
            return Mage::helper("amrma")->getIsEnablePerItem();
        }
    }
?>