<?php
/**
 * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
 */ 
class Amasty_Rma_Model_Mysql4_Request extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        $this->_init('amrma/request', 'request_id');
    }
        
    public function getStatus($statusId){
        return Mage::getModel('amrma/status')->load($statusId);
    }
}
?>