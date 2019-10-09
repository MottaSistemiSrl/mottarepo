<?php
/**
 * SevenLike Payment Front Controller
 *
 * @category   SevenLike
 * @package    SevenLike_Bancasellagestpay
 * @name       SevenLike_Bancasellagestpay_SevenLikeController
 * @author     Sevenlike srl <www.sevenlike.com> && Daniele Gagliardi <www.danielegagliardi.it> 
 */
class Sevenlike_Bancasellagestpay_ResultController extends Mage_Core_Controller_Front_Action
{
    /**
     * Get Checkout Singleton
     *
     * @return Mage_Checkout_Model_Session
     */
    
    /**
     * Order instance
     */
    protected $_order;
    
    public function getCheckout()
    {
        return Mage::getSingleton('checkout/session');
    }
    
    public function getOrder()
    {
        if ($this->_order == null) {
            $session = Mage::getSingleton('checkout/session');
            $this->_order = Mage::getModel('sales/order');
            $this->_order->loadByIncrementId($session->getLastRealOrderId());
        }
        return $this->_order;
    }
    
    public function redirectAction()
    {
	    $session = Mage::getSingleton('checkout/session');

	    $order = $this->getOrder();

	    if (!$order->getId()) {
		    $this->norouteAction();
		    return;
	    }

	    $order->addStatusToHistory(
		    $order->getStatus(),
		    $this->__('Reindirizzamento su Gestpay avvenuto')
	    );
	    $order->save();

	    $this->getResponse()
		    ->setBody($this->getLayout()
			    ->createBlock('bancasellagestpay/redirect')
			    ->setOrder($order)
			    ->toHtml());

    }

    public function rejectAction()
    {
    	
    }

    public function exceptionAction()
    {
    
    }

    public function successAction()
    {
        //ricevo i dati da gestpay e li decripto
        $params = $this->decryptGestPayParams();

        //se la transazione � ok proseguo
        if ($params['result'] == 'OK')
        {
            //svuoto il carrello
            $session = $this->getCheckout();
            $session->setQuoteId($session->getBancaSellaGestPayQuoteId(true));
            $session->getQuote()->setIsActive(false)->save();
            $session->unsBancaSellaGestPayQuoteId();

            $this->_redirect('checkout/onepage/success');
            return;

        } else {
            $order->cancel();
            $order->addStatusToHistory($order->getStatus(), $this->__('Errore nel pagamento su GestPay'));

                $session = Mage::getSingleton('checkout/session');
                $session->addError($this->__('Pagamento non completato a causa di un errore nel pagamento su Banca Sella, per favore riprova.'));
                $this->_redirect('checkout/cart');
            return;
        }
    }

    public function processAction() {
	//ricevo i dati da gestpay e li decripto
	$params = $this->decryptGestPayParams();

	//salvo lo stato dell'ordine
	$order = Mage::getModel('sales/order');
	$order->loadByIncrementId($params['orderId']);
	
	if ($params['result'] == 'OK')
	{
	    $order->addStatusToHistory(
		    $order->getStatus(),
				    $this->__('Pagamento da GestPay completato con successo')
		    );
	    
	    //imposto l'ordine in elaborazione in base a come impostato nel'admin
	    $bancasellagestpay = Mage::getModel ( 'bancasellagestpay/standard' );
	    $setStatus = $bancasellagestpay->getConfigData ( 'order_status_paid' );
	    
	    //$order->setState(Mage_Sales_Model_Order::STATE_PROCESSING, true)->save();
    
	    if ($order->canInvoice() && $setStatus > 1) {
		$invoice = $order->prepareInvoice();
		$invoice->register()->capture();
		Mage::getModel('core/resource_transaction')
		    ->addObject($invoice)
		    ->addObject($invoice->getOrder())
		    ->save();
                $order->addStatusToHistory(
                    "processing", $this->__('Ordine in elaborazione')
                );

	    }
	    
	    if($setStatus == 3)
	    {
		$order->setStatus("complete");
	    }
	    

	    $order->sendNewOrderEmail();
	    $order->save();
	
	} else {
	    $errorMsg = $this->__('Payment failed or canceled. ');
	    if (!$order->getId()) {
		$this->norouteAction();
		return;
	    }
		$order->addStatusToHistory(
		    "canceled",
		    $this->__('Ritorno da Gestpay con errore: ') . $errorMsg
		);
	    
	    //annullo l'ordine
	    $order->cancel()->save();
	    
	}

    }

    
    public function errorAction()
    {
    	$session = Mage::getSingleton('checkout/session');
        $errorMsg = $this->__('Payment failed or canceled. ');
	$session->addError($errorMsg);
        
        //Mage::getSingleton('checkout/session')->unsLastRealOrderId();
        $session = $this->getCheckout();
	
	$session->addError($this->__('Order canceled, please try again.'));
	
	//Ripristino il carrello
	$session = Mage::getSingleton('checkout/session');
	$cart = Mage::getSingleton('checkout/cart');
	$cart->save();
        
	$this->_redirect('checkout/cart');
    }
    
    public function decryptGestPayParams()
    {
        $GestPayCrypt = Mage::getModel ( 'bancasellagestpay/crypt' );
        
        if (empty($_GET["a"])) {
            die("Parametro mancante: 'a'\n");
        }
        
        if (empty($_GET["b"])) {
            die("Parametro mancante: 'b'\n");
        }
        
	$bancasellagestpay = Mage::getModel ( 'bancasellagestpay/standard' );
	$domain = $bancasellagestpay->getConfigData ( 'submit_url' );
	
        $crypt = new GestPayCrypt($domain);

        $crypt->SetShopLogin($_GET["a"]);
        $crypt->SetEncryptedString($_GET["b"]);
        
        if (!$crypt->Decrypt()) {
                die("Error: ".$crypt->GetErrorCode().": ".$crypt->GetErrorDescription()."\n");
        }
        
        $result = $crypt->GetTransactionResult();
        $orderId = $crypt->getShopTransactionID();
        
        return array('result' => $result, 'orderId' => $orderId);
    }
}
