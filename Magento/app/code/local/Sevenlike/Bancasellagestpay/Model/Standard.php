<?php
/**
 * SevenLike Payment Front Controller
 *
 * @category   SevenLike
 * @package    SevenLike_BancaSellaGestpay
 * @name       SevenLike_BancaSellaGestpay_SevenLikeController
 * @author     Sevenlike srl <www.sevenlike.com> && Daniele Gagliardi <www.danielegagliardi.it> 
 */

class Sevenlike_Bancasellagestpay_Model_Standard extends Mage_Payment_Model_Method_Abstract
{

    protected $_code = 'bancasellagestpay';
    protected $_formBlockType = 'bancasellagestpay/form';
    
    protected $_customer;
    protected $_checkout;
    protected $_quote;
    protected $_order;
    protected $_allowCurrencyCodes;
    protected $_allowedParamToSend;
    
    /**
     * Availability options
     */
    protected $_isGateway               = true;
    protected $_canAuthorize            = true;
    protected $_canCapture              = true;
    protected $_canCapturePartial       = false;
    protected $_canRefund               = false;
    protected $_canVoid                 = false;
    protected $_canUseInternal          = false;
    protected $_canUseCheckout          = true;
    protected $_canUseForMultishipping  = true;

    protected $moduleTitle;
    protected $moduleDebugMode;
    
    
    public function getGestpayUrl()
    {
        $domain = $this->getConfigData ( 'submit_url' );
        return "https://".$domain."/gestpay/pagam.asp"; 
    }
    
    public function addGestpayFields ($form)
    {
        $GestPayCrypt = Mage::getModel ( 'bancasellagestpay/crypt' );
        $domain = $this->getConfigData ( 'submit_url' );
        
        $objCrypt = new GestPayCrypt($domain);
        
        //recupero i dati dell'ordine
        $session = Mage::getSingleton('checkout/session');
        $orderId = $session->getLastRealOrderId();
        $order = Mage::getSingleton('sales/order');
        $order->load($orderId);
        $grandTotal = round($this->getOrder()->getBaseGrandTotal(), 2);
                
        
        //setto le variabili da criptare
        $myshoplogin = $this->getConfigData ( 'merchant_id' );
        $mycurrency = "242"; //Es. 242 per euro
        $myamount = $grandTotal; // Es. 1256.28
        $myshoptransactionID = $orderId; 
        
        $objCrypt->SetShopLogin($myshoplogin);
        $objCrypt->SetCurrency($mycurrency);
        $objCrypt->SetAmount($myamount);
        $objCrypt->SetShopTransactionID($myshoptransactionID);
        
        $objCrypt->Encrypt();

        $ed=$objCrypt->GetErrorDescription();
        
        if($ed!="")
        {
            echo "Errore di encoding: " . $objCrypt->GetErrorCode() . " " . $ed . "<br />";
            echo $orderId;
            echo "<br />";
            print_r($order);
            echo "<br />";
            echo $grandTotal;
            die;
        }
        else
        {
            $b = $objCrypt->GetEncryptedString();
            $a = $objCrypt->GetShopLogin();
    
            $form->addField("a", 'hidden', array('name' => 'a', 'value' => $a));
            $form->addField("b", 'hidden', array('name' => 'b', 'value' => $b));
            return $form;
        }
    }
    
   
    
    /**
    * Return Order place redirect url
    *
    * @return string
    */
    public function getOrderPlaceRedirectUrl()
    {
        return Mage::getUrl('bancasellagestpay/result/redirect');
    }
    
    public function getOrder()
    {
        if (empty($this->_order)) {
            $order = Mage::getModel('sales/order');
            $order->loadByIncrementId($this->getCheckout()->getLastRealOrderId());
            $this->_order = $order;
        }
        return $this->_order;
    }
    
    public function getCheckout()
    {
        if (empty($this->_checkout)) {
            $this->_checkout = Mage::getSingleton('checkout/session');
        }
        return $this->_checkout;
    }
    
    protected function getSuccessURL()
    {
	return Mage::getUrl('/bancasellagestpay/result/success');
    }

    protected function getErrorURL()
    {
        return Mage::getUrl('/bancasellagestpay/result/error');
    }
    
    
    public function stripSL($d, $str, $ctot = "")
    {
        $a = str_split(strtoupper($d));
        
        $ctot = "";
        
        foreach ($a as $c){
            $pos = strpos($str, "9".$c);
            $start = $pos + 2;
            $end = 3; 
            $c2 = substr($str, $start, $end);
            $ctot .= $c2;
        }
	
        return $ctot;
    }
}