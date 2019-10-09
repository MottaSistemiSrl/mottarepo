<?php
/**
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */
class Bitbull_BancaSellaPro_GestpayController extends Mage_Core_Controller_Front_Action {

    private $_order,$_profile;

    public function getOrder()
    {
        if ($this->_order == null) {
            $session = Mage::getSingleton('checkout/session');
            $this->_order = Mage::getModel('sales/order');
            $this->_order->loadByIncrementId($session->getLastRealOrderId());
        }
        return $this->_order;
    }

    public function getRecurringProfiles(){
        if ($this->_profile == null) {
            $profileIds = Mage::getSingleton('checkout/session')->getLastRecurringProfileIds();
            if ($profileIds && is_array($profileIds)) {
                $collection = Mage::getModel('sales/recurring_profile')->getCollection()
                    ->addFieldToFilter('profile_id', array('in' => $profileIds))
                ;
                $profiles = array();
                foreach ($collection as $profile) {
                    $profiles[] = $profile;
                }
                if ($profiles) {
                    $this->_profile = $profiles;
                }
            }
        }
        return $this->_profile;

    }


    public function redirectAction(){
        $order = $this->getOrder();

        if (!$order->getId()) {
            $this->norouteAction();
            return;
        }

        $order->addStatusHistoryComment(  $this->__('Reindirizzamento su Gestpay avvenuto'));
        $order->save();

        /** @var Bitbull_BancaSellaPro_Helper_Data $_helper */
        $_helper= Mage::helper('bitbull_bancasellapro');
        $_helper->log('Reindirizzamento utente sul sito di bancasella dopo aver effettuato l\'ordine con id='.$order->getId());

//        $session = Mage::getSingleton('checkout/session');
//        $session->clear();

        Mage::register('current_order', $order);

        try{

            $this->loadLayout();
            $this->renderLayout();

        }catch (Exception $e){
            $_helper->log($e->getMessage());
            $session = Mage::getSingleton('checkout/session');
            $session->addError($this->__('Pagamento non completato a causa di un errore nel pagamento su Banca Sella.'));
            $this->_redirect('checkout/cart');
            return;
        }

    }

    public function resultAction(){

        $a = $this->getRequest()->getParam('a',false);
        $b = $this->getRequest()->getParam('b',false);

        $_helper= Mage::helper('bitbull_bancasellapro');


        if(!$a || !$b){
            $_helper->log('Accesso alla pagina per il risultato del pagamento non consentito, mancano i parametri di input');
            $this->norouteAction();
            return;
        }

        Mage::register('bancasella_param_a', $a);
        Mage::register('bancasella_param_b', $b);

        /** @var Bitbull_BancaSellaPro_Helper_Crypt $helper */
        $helper= Mage::helper('bitbull_bancasellapro/crypt');

        if( $helper->isPaymentOk( $a , $b )){
            $_helper->log('L\'utente ha completato correttamente l\'inserimento dei dati su bancasella');
            $redirect ='checkout/onepage/success';// '*/*/success';
        }
        else{
            $_helper->log('L\'utente ha annullato il pagamento, oppure qualche dato non corrisponde');

            $session = Mage::getSingleton('checkout/session');
            $session->addError($this->__('Pagamento non completato su Banca Sella.'));
            $redirect = 'checkout/cart';

        }
        //se è impostato lo store allora reindirizzo l'utente allo store corretto
        $store= Mage::registry('bitbull_bancasellapro_store_maked_order');
        if($store && $store->getId()){
            $this->redirectInCorrectStore( $store, $redirect );
        }else{
            $this->_redirect($redirect);
        }
        return;


    }

    public function s2sAction(){
        $a = $this->getRequest()->getParam('a',false);
        $b = $this->getRequest()->getParam('b',false);
        /** @var Bitbull_BancaSellaPro_Helper_Data $_helper */

        $_helper= Mage::helper('bitbull_bancasellapro');

        if(!$a || !$b){
            $_helper->log('Richiesta S2S, mancano i parametri di input');
            $this->norouteAction();
            return;
        }

        Mage::register('bancasella_param_a', $a);
        Mage::register('bancasella_param_b', $b);

        /** @var Bitbull_BancaSellaPro_Helper_Crypt $helper */
        $helper= Mage::helper('bitbull_bancasellapro/crypt');

        $webservice = $helper->getInitWebservice();

        $webservice->setDecryptParam($a , $b);
        $helper->decriptPaymentRequest ($webservice);

        $orderId = $webservice->getShopTransactionID();
        $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);

        if($order->getId()){
            $_helper->log('Imposto lo stato dell\'ordine in base al decrypt');

            $helper->setStatusOrderByS2SRequest($order,$webservice);
            Mage::helper('bitbull_bancasellapro/recurringprofile')->checkAndSaveToken($order,$webservice);
        }else{
            $_helper->log('La richiesta effettuata non ha un corrispettivo ordine. Id ordine= '.$webservice->getShopTransactionID());
        }

        //restiutisco una pagina vuota per notifica a GestPay
        $this->getResponse()
            ->setBody('<html></html>');
        return;
    }


    public function successAction(){
        $order = $this->getOrder();
        if (!$order->getId()) {
            $this->_redirect('checkout/cart');
            return;
        }

        $session = Mage::getSingleton('checkout/session');
        $session->clear();

        $this->loadLayout();
        $this->_initLayoutMessages('checkout/session');
        Mage::dispatchEvent('bitbull_bancasellapro_gestpay_success_action', array('order_ids' => array($order->getId())));
        $this->renderLayout();
    }


    public function confirm3dAction(){
        $_helper= Mage::helper('bitbull_bancasellapro');
        $_helper->log('Richiamata azione conferma 3dsecure');
        $_helper->log($_REQUEST);
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function redirectInCorrectStore($store, $path, $arguments = array())
    {
        $params = array_merge(
            $arguments,
            array(
                '_use_rewrite' => false,
                '_store' => $store,
                '_store_to_url' => true,
                '_secure' => $store->isCurrentlySecure()
            ) );
        $url = Mage::getUrl($path,$params);

        $this->getResponse()->setRedirect($url);
        return;
    }


}