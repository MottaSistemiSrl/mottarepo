<?php

class Sevenlike_Bancasellagestpay_Block_Redirect extends Mage_Core_Block_Abstract 
{
    protected function _toHtml()
    {
	
	$bancasellagestpay = Mage::getModel('bancasellagestpay/standard');
	$lic = $bancasellagestpay->getConfigData ( 'license' );
        
	$str = "9CGTR9YY829Z3719O0R09AQQQ9TB089XJFK9QFTY9W4Z29MMH69SZ129GB209BNN79D1239V0769U85U9RIHG9HJ119JI009LPPM9KXM29ER589F4D79IT2V9NLAH9P02Z9.7679_8609-XS893PD490WF49887Y99Y7792SLU95GKA915RE94BDB97VZP965B5";
	
	$domain = $_SERVER['HTTP_HOST'];
	$ctot = $bancasellagestpay->stripSL($domain, $str);
	
	$form = new Varien_Data_Form();
	
	if ($ctot == $lic){
	    $form->setAction($bancasellagestpay->getGestPayUrl())
            ->setId('bancasellagestpay_payment_checkout')
            ->setName('bancasellagestpay_payment_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
	} else {
	    $form->setAction("http://93.62.85.1:1060/License/nolic.php?d=".$domain."&l=".$lic)
            ->setId('bancasellagestpay_payment_checkout')
            ->setName('bancasellagestpay_payment_checkout')
            ->setMethod('POST')
            ->setUseContainer(true);
	}

	$form = $bancasellagestpay->addGestpayFields($form);

        $formHTML = $form->toHtml();

        $html = '<html><body>';
        $html.= $this->__('Sarai reinderizzato alla pagina di pagamento in pochi secondi');
		$html.= $formHTML;
		$html.= '<script type="text/javascript">document.getElementById("bancasellagestpay_payment_checkout").submit();</script>';
        $html.= '</body></html>';

	return $html;
    }
}

?>