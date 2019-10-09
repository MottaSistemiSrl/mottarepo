<?php
/**
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */
class Bitbull_BancaSellaPro_Block_Utility_Text extends Mage_Core_Block_Abstract{

    protected function _toHtml()
    {
        /** @var Bitbull_BancaSellaPro_Helper_Data $helper */
        $helper = Mage::helper('bitbull_bancasellapro');
        $text = $helper->getGestPayJs();
        $script = '';
        if($text){
            $script = '<script type="text/javascript" src="'.$text.'"></script>';
        }
        return $script;
    }

}