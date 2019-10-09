<?php
class Sevenlike_Bancasellagestpay_Block_Form extends Mage_Payment_Block_Form
{

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sevenlike/bancasellagestpay/form.phtml');
    }
}
