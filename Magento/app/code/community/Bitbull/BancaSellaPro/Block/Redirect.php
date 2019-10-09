<?php
/**
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class Bitbull_BancaSellaPro_Block_Redirect  extends Mage_Page_Block_Redirect
{

    /**
     * Confiniamo i template per il frontend
     * nella cartella del modulo.
     */
//    public function fetchView($fileName)
//    {
//        $this->setScriptPath(Mage::getModuleDir('', 'Bitbull_BancaSellaPro') . DS . 'templates'. DS .'frontend');
//
//        return parent::fetchView($this->getTemplate());
//    }
//
//    protected function _getAllowSymlinks()
//    {
//        return true;
//    }


    public function getOrder()
    {
        return Mage::registry('current_order');
    }

    /**
     *  Get target URL
     *
     *  @return string
     */
    public function getTargetURL ()
    {
        if(!$this->getCalculateTargetUrl()){
            $helper = Mage::helper('bitbull_bancasellapro');
            $this->setCalculateTargetUrl( $helper->getRedirectUrlToPayment($this->getOrder()));
        }
        return $this->getCalculateTargetUrl();

    }


    public function getMethod ()
    {
        return 'GET';
    }

    public function getMessage ()
    {
        return $this->__('A breve sarai reindirizzato su BancaSellaPro, attendi un attimo');
    }

}
