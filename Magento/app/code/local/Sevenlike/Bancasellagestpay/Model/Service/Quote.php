<?php

class Sevenlike_Bancasellagestpay_Model_Service_Quote extends Mage_Sales_Model_Service_Quote
{
    public function submitOrder()
    {
        $order = parent::submitOrder();
        
        // Prevent the cart to be emptied before payment response 
        if ($order->getPayment()->getMethod() == "bancasellagestpay")
            $this->_quote->setIsActive(true);
        
        return $order;
    }
}
