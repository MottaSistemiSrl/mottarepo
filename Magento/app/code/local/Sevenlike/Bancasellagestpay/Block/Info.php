<?php

class Sevenlike_Bancasellagestpay_Block_Info extends Mage_Core_Block_Template
{

    protected function _construct()
    {
        $this->setTemplate('sevenlike/bancasellagestpay/info.phtml');
        parent::_construct();
    }
	
    public function toPdf()
    {
        $this->setTemplate('sevenlike/bancasellagestpay/info.phtml');
        return $this->toHtml();
    }
}
