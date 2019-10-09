<?php
/**
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */
class Bitbull_BancaSellaPro_Block_Confirm3d extends Bitbull_BancaSellaPro_Block_Abstract {

    public function getPARes(){
        return $this->getRequest()->get('PaRes');
    }

    public function getCartUrl(){
        return Mage::getUrl('checkout/cart',array('_secure' => Mage::app()->getStore()->isCurrentlySecure()));
    }

}