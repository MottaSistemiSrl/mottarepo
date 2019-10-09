<?php

class Amm_Product_StatisticaController extends Mage_Core_Controller_Front_Action
{
	
	protected function _getSession()
    {		
        return Mage::getSingleton('customer/session');
    }

	public function indexAction()
    { 
    	$session = $this->_getSession();

         Mage::helper('product')->checkTable();

    	if(!$session->isLoggedIn()){
    		$this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
    	}else{

		    $this->loadLayout();

            $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
            if ($navigationBlock) {
                $navigationBlock->setActive('product/statistica');
            }

        $this->renderLayout();
        
      }
    }
}