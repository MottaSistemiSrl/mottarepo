<?php
class Sevenlike_Fatturazione_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getImponibileScontoPdf($item){
        $taxPercent = $item->getOrderItem()->getTaxPercent()/100;
        $taxPercent = 1+$taxPercent;
        $imponibile = $item->getQty()*($item->getOrderItem()->getProduct()->getPrice());
        $discCoupon = 0;
        if($item->getDiscountAmount()!=0){
            $discCoupon = $item->getDiscountAmount()- $item->getHiddenTaxAmount();
        }
        $discount = $item->getQty()*($item->getOrderItem()->getProduct()->getPrice() - $item->getPrice()) + $discCoupon ;

        return array('imponibile'=>$imponibile-$discount,'sconto'=>$discount,'presconto'=>$imponibile);


    }
}
	 