<?php
/**
 * Class     Wss2s.php
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@gmail.com>
 */
class Bitbull_BancaSellaPro_Model_Webservice_Wss2s extends Bitbull_BancaSella_Model_Webservice_Abstract{

    const PATH_WS_CRYPT_DECRIPT = '/gestpay/gestpayws/WSS2S.asmx?WSDL';

    public function getWSUrl(){
        return $this->url_home . self::PATH_WS_CRYPT_DECRIPT;
    }


    /**
     * metodo che imposta i dati dell'ordine all'interno
     * @param Mage_Sales_Model_Order $order
     */
    public function setOrder(Mage_Sales_Model_Order $order){

        /**@var $gestpay Bitbull_BancaSella_Model_Gestpay */
        $gestpay = $order->getPayment()->getMethodInstance();
        $total = $gestpay->getTotalByOrder($order);

        if($gestpay instanceof Bitbull_BancaSellaPro_Model_Gestpay){

            $this->setData('shopLogin', $gestpay->getMerchantId() );
            $this->setData('shopTransactionId', $order->getIncrementId() );
            $this->setData('uicCode', $gestpay->getCurrency() );
            $this->setData('languageId', $gestpay->getLanguage() );
            $this->setData('amount', round($total, 2) );

        }

    }

    public function setToken(Bitbull_BancaSellaPro_Model_Token $token){
        $this->setData('tokenValue',$token->getToken());
    }

    /**
     * metodo che restituisce i parametri per creare la stringa criptata per effettuare una richiesta di pagamento a bancasella
     * @return array
     */
    public function getParamToCallPagamS2S(){
        $_helper= Mage::helper('bitbull_bancasellapro');
        $_helper->log('Imposto i parametri da inviare all\'encrypt per effettuare il pagamento con token');

        $param = array();
        $param['shopLogin'] =  $this->getData('shopLogin');
        $param['shopTransactionId'] =  $this->getData('shopTransactionId');
        $param['uicCode'] =  $this->getData('uicCode');

        if($this->getData('languageId')!=0){
            $param['languageId'] =  $this->getData('languageId');
        }
        $param['amount'] = $this->getData('amount');
        $param['tokenValue'] = $this->getData('tokenValue');

        $_helper->log($param);

        return $param;
    }

    /**
     * metodo che importa i risultati del decrypt
     * @param $result
     */
    public function setResponseCallPagamS2S($result){

        $_helper= Mage::helper('bitbull_bancasellapro');
        $_helper->log('Salvo i parametri decriptati');

        $realResult = simplexml_load_string($result->callPagamS2SResult->any);

        $this->setTransactionType((string)$realResult->TransactionType);
        $this->setTransactionResult((string)$realResult->TransactionResult);
        $this->setErrorCode((string)$realResult->ErrorCode);
        $this->setErrorDescription((string)$realResult->ErrorDescription);


        $this->setShopTransactionID((string)$realResult->ShopTransactionID);
        $this->setBankTransactionID((string)$realResult->BankTransactionID);
        $this->setAuthorizationCode((string)$realResult->AuthorizationCode);
        $this->setCurrency((string)$realResult->Currency);
        $this->setAmount((string)$realResult->Amount);
        $this->setCountry((string)$realResult->Country);
        $this->setCustomInfo((string)$realResult->CustomInfo);
        $this->setBuyerName((string)$realResult->Buyer->BuyerName);
        $this->setBuyerEmail((string)$realResult->Buyer->BuyerEmail);
        $this->setTDLevel((string)$realResult->TDLevel);
        $this->setAlertCode((string)$realResult->AlertCode);

        $this->setAlertDescription((string)$realResult->AlertDescription);
        $this->setVbVRisp((string)$realResult->VbVRisp);
        $this->setVbVBuyer((string)$realResult->VbVBuyer);
        $this->setVbVFlag((string)$realResult->VbVFlag);
        $this->setTransactionKey((string)$realResult->TransactionKey);
        $this->setPaymentMethod((string)$realResult->PaymentMethod);

        //token
        $this->setData('token',(string)$realResult->TOKEN);
        $this->setTokenExpiryMonth((string)$realResult->TokenExpiryMonth);
        $this->setTokenExpiryYear((string)$realResult->TokenExpiryYear);

        $_helper->log($this->getData());

    }
}