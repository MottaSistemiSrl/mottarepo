<?php
/**
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class Bitbull_BancaSellaPro_Helper_Crypt extends Bitbull_BancaSellaPro_Helper_Baseclient{

    protected $_webserviceClassName ='bitbull_bancasellapro/webservice_wscryptdecrypt';
    /**
     * Effettua l'encypt dei dati memorizzati nel webserice
     * @param $webService contiene le info da criptare
     *
     * @return string stringa criptata
     */
    protected function getEncryptString($webService){

        $client = $this->_initClient($webService);
        if(!$client){
            return false;
        }

        $param = $webService->getParamToEncrypt();

        $result = $client->Encrypt($param);

        $webService->setResponseEncrypt($result);

        return $webService->getCryptDecryptString();

    }

    /**
     * Funzione che dall'ordine restituisce la stringa criptata delle sue info
     * @param $order ordine da criptare
     *
     * @return mixed stringa criptate
     */
    public function  getEncryptStringByOrder ($order){
        $method = $order->getPayment()->getMethodInstance();
        /** @var $webService Bitbull_BancaSellaPro_Model_Webservice_Wscryptdecrypt  */
        $webService = Mage::getModel('bitbull_bancasellapro/webservice_wscryptdecrypt');
        $webService->setOrder($order);
        $webService->setBaseUrl($method->getBaseWSDLUrlSella());

        return $this->getEncryptString($webService);

    }

    public function  getEncryptStringByOrderWitTokenRequest ($order){
        $method = $order->getPayment()->getMethodInstance();
        /** @var $webService Bitbull_BancaSellaPro_Model_Webservice_Wscryptdecrypt  */
        $webService = Mage::getModel('bitbull_bancasellapro/webservice_wscryptdecrypt');
        $webService->setOrder($order);
        $webService->setRecurringProfile(true);
        $webService->setBaseUrl($method->getBaseWSDLUrlSella());

        return $this->getEncryptString($webService);

    }

    /**
     * @param $method Bitbull_BancaSellaPro_Model_Gestpay
     *
     * @return string
     */
    public function  getEncryptStringBeforeOrder ($method){
        /** @var Bitbull_BancaSellaPro_Model_Webservice_Wscryptdecrypt $webService */
        $webService = Mage::getModel('bitbull_bancasellapro/webservice_wscryptdecrypt');

        $webService->setInfoBeforeOrder($method);
        $webService->setBaseUrl($method->getBaseWSDLUrlSella());

        return $this->getEncryptString($webService);

    }


    /**
     * funzione che si occupa di decriptare i dati ricevuti da gestpay
     * @param $webService
     *
     * @return mixed
     */
    public function decriptPaymentRequest($webService){

        $client = $this->_initClient($webService);
        if(!$client){
            return false;
        }

        $param = $webService->getParamToDecrypt();

        $result = $client->Decrypt($param);

        $webService->setResponseDecrypt($result);

        return $webService;

    }

    public function isPaymentOk($a , $b ){
        $_helper= Mage::helper('bitbull_bancasellapro');

        $webService =$this->getInitWebservice();

        $webService->setDecryptParam($a , $b);

        $result = $this->decriptPaymentRequest ($webService);

        if(!$result){
            return false;
        }

        $orderId = $webService->getShopTransactionID();
        /** @var Mage_Sales_Model_Order $order */

        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
        //salvo lo store per effettuare il redirect a completamento della verifica
        Mage::register('bitbull_bancasellapro_store_maked_order',$order->getStore());
        Mage::register('bitbull_bancasellapro_order',$order);

        if($order->getId()){

            if($webService->getFastResultPayment()){
                $_helper->log('Il web service ha dato esito positivo al pagamento');

                //controllo se la richiesta s2s è già stata elaborata
                if(!$_helper->isElaborateS2S($order)){
                    $_helper->log('La transazione non è ancora stata inviata sul s2s');

                    //in questo punto l'utente ha completato l'ordine ma aspettiamo la chiamata s2s per confermare lo stato
                    if($order->getId()){
                        $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true,$this->__('Attesa conferma pagamento da Banca Sella'));
                        $order->save();
                    }
                }else{
                    $_helper->log('La tranzazione è gia stata salvata, non cambio lo stato');
                }

                if( $order->getStatus() != Mage_Sales_Model_Order::STATUS_FRAUD){
                    $_helper->log('Invio email di conferma creazione ordine all\'utente');
                    $order->sendNewOrderEmail();
                }
                return true;
            }else{
                $_helper->log('Il web service ha restituito KO');

                //in questo punto l'ordine è stato annullato dall'utente
                if($order->getId()){
                    $method= $order->getPayment()->getMethodInstance();
			//MODIFICA SL
                    $order->setState($method->getOrderStatusKoUser(), true,$this->__('L\'utente ha annullato la transazione'));
                    $order->cancel();
		    $order->save();
                }
                return false;
            }
        }else{
            $_helper->log('L\'ordine restituito da bancasella non esiste. Increment id= '.$orderId);
            return false;
        }
    }

    public function getInitWebservice(){
        $webService = Mage::getModel('bitbull_bancasellapro/webservice_wscryptdecrypt');
        $gestPay=Mage::getModel('bitbull_bancasellapro/gestpay');
        $webService->setBaseUrl($gestPay->getBaseWSDLUrlSella());

        return $webService;
    }

    public function setStatusOrderByS2SRequest($order, $webservice){
        $method= $order->getPayment()->getMethodInstance();
        $_helper= Mage::helper('bitbull_bancasellapro');


        if($method->getConfigData('order_status_fraud_gestpay')){
            $_helper->log('Controllo frode');

            $message=false;
            $total= $method->getTotalByOrder($order);
            $_helper->log('controllo il totale dell\'ordine : ' .$webservice->getAmount(). ' = '.round($total, 2));
            if (round($webservice->getAmount(), 2) != round($total, 2)){
                //il totatle dell'ordine non corrisponde al totale della transazione
                $message = 'Il totale della tranzazione non corrisponde';
            }
            if ($webservice->getAlertCode()!=''){
                $_helper->log('controllo alert della transazione : ' .$webservice->getAlertCode());

                $message = $webservice->getAlertDescription();
            }
            if($message){
                $_helper->log('rilevata possibile frode: '.$message);

                $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, Mage_Sales_Model_Order::STATUS_FRAUD, $message);
                $order->save();
                return false;
            }
        }

        switch ($webservice->getTransactionResult()){
            case Bitbull_BancaSellaPro_Model_Webservice_Wscryptdecrypt::TRANSACTION_RESULT_PENDING :
                $order->setState(Mage_Sales_Model_Order::STATE_PENDING_PAYMENT, true);
                $_helper->log('Pagamento effettuato con bonifico bancario, verificare a mano la transazione');
                $order->addStatusHistoryComment(  $this->__('Pagamento con bonifico bancario'));
                break;
            case Bitbull_BancaSellaPro_Model_Webservice_Wscryptdecrypt::TRANSACTION_RESULT_OK :
                $_helper->log('Pagamento effettuato correttamente. Cambio stato all\'ordine e salvo l\'id della transazione');
                $order->setState($method->getOrderStatusOkGestPay(), true);
                $testo= strlen($webservice->getPaymentMethod())>0 ? '('.$webservice->getPaymentMethod().')' : '';
                $order->addStatusHistoryComment(  $this->__('Id transazione banca:' .$webservice->getBankTransactionID()).$testo);
                break;
            case Bitbull_BancaSellaPro_Model_Webservice_Wscryptdecrypt::TRANSACTION_RESULT_KO :
                $_helper->log('Pagamento non andato a buon fine. Cambio stato all\'ordine e salvo l\'id della transazione');
                //MODIFICA SL               
		//$order->setState($method->getOrderStatusKoGestPay(), true);
                $order->cancel();
		$testo= strlen($webservice->getPaymentMethod())>0 ? '('.$webservice->getPaymentMethod().')' : '';
                $order->addStatusHistoryComment(  $this->__('Id transazione banca:' .$webservice->getBankTransactionID()).$testo);
                break;
        }
        $order->save();

    }
}
