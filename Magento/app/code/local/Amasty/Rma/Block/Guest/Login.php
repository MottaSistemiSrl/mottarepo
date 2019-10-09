<?php
/**
 * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
 */ 
class Amasty_Rma_Block_Guest_Login extends Mage_Core_Block_Template
{
    private $_username = -1;
    
    protected function _prepareLayout()
    {
        $this->getLayout()->getBlock('head')->setTitle(Mage::helper('amrma')->__('RMA Login'));
        return parent::_prepareLayout();
    }
    
    /**
     * Retrieve username for form field
     *
     * @return string
     */
    public function getUsername()
    {
        if (-1 === $this->_username) {
            $this->_username = Mage::getSingleton('amrma/session')->getUsername(true);
        }
        return $this->_username;
    }
    
    public function getLoginPostUrl(){
        return Mage::helper('amrma')->getLoginPostUrl();
    }
}
?>