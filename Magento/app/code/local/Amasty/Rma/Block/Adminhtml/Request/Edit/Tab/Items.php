<?php
    /**
    * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
    */ 
    class Amasty_Rma_Block_Adminhtml_Request_Edit_Tab_Items extends Mage_Adminhtml_Block_Widget_Form
    {
        public function __construct()
        {
            parent::__construct();
            $this->setTemplate('amasty/amrma/request/items.phtml');
        }
        
        public function getRmaItems(){
            
            $collection = Mage::getModel('amrma/item')
                    ->getCollection()
                    ->addFilter('request_id', $this->getModel()->getId());
            
            return $collection;
        }
    }
?>