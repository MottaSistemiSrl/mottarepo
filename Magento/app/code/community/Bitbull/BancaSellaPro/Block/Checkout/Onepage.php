<?php
/**
 * @category Bitbull_BancaSellaPro
 * @package  Bitbull_BancaSellaPro_${MODULE}
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class Bitbull_BancaSellaPro_Block_Checkout_Onepage extends Mage_Checkout_Block_Onepage{


    //rimosso il rewrite che richiude i template all'interno del modulo di bancasella pro
//    /**
//     * Confiniamo i template per il frontend
//     * nella cartella del modulo.
//     */
//    public function fetchView($fileName)
//    {
//        $this->setScriptPath(Mage::getModuleDir('', 'Bitbull_BancaSellaPro') . DS . 'templates'.DS.'frontend');
//
//        return parent::fetchView($this->getTemplate());
//    }
//
//    protected function _getAllowSymlinks()
//    {
//        return true;
//    }
//
//    public function __construct ()
//    {
//        parent::__construct ();
//
////        $block = $this->getLayout()->createBlock('bitbull_bancasellapro/utility_text','gestpay.iframe.external');
////
////        $this->getLayout()->getBlock('head')
////            ->append($block)
////            ->addJs('prototype/window.js')
////            ->addItem('js_css','prototype/windows/themes/default.css')
////            ->addCss('lib/prototype/windows/themes/magento.css');
//    }


}