<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 15/11/13
 * Time: 12.53
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Model_Pdf_Total_Subtotal extends Mage_Sales_Model_Order_Pdf_Total_Default{

    public function getAmount(){
		if($this->getSource()->getDiscountAmount()!=0){
			$disc = $this->getSource()->getDiscountAmount();
			if($disc > 0){
				$disc = -1*$disc;
			}
			$disc += $this->getSource()->getHiddenTaxAmount();
		} else{
			$disc = 0;
		}

        return $this->getSource()->getSubtotal() + $disc;
    }
   
}
