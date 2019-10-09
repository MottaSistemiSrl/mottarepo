<?php

    class MG_Megaforum_Model_Observer {
	
        public function customerReg($observer) {
		
        $event = $observer->getEvent(); 
        $customerId = $event->getCustomer()->getId();
		
		$model = Mage::getModel("megaforum/forumuser")
		->setUserId($customerId)
		->setUserType('Normal')
		->save(); 


        }
    }	 