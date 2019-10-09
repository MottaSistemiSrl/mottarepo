<?php
/**
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class Bitbull_BancaSellaPro_Model_Observer extends Mage_Core_Model_Abstract{

    /**
     * Metodo richiamato quando viene richiesto il salvataggio del metodo di pagamento con il modulo IWD_Opc
     * @param $observer
     */
    public function addDataToResultSavePaymentIWD($observer){

        $sendParam = $observer->getResult();
        $json = $sendParam->getJson();

        if($encryptString = $observer->getMethod()->getEncryptString()){
            //se il metodo ha l'encryptString allora lo salvo nel result
            $json['encrypt_string'] = $encryptString;
        }
        //fix per quando non c'� l'iframe e serve salvare l'ordine
        if($observer->getMethod()->getCode()=='free'){
            $json['free'] = true;
        }

        $observer->getResult()->setJson($json);
    }

    /**
     * Metodo che si occupa dell'esecuzione dei pagamenti per i profili ricorrenti
     * @param $observer
     */
    public function chargeRecurringProfiles(){

        //recuperare tutte i profili attivi da eseguire
        $recurringProfieles = Mage::getModel('sales/recurring_profile')
            ->getCollection()
            ->addFieldToFilter('method_code', Bitbull_BancaSellaPro_Model_Gestpay::METHOD_CODE)
            ->addFieldToFilter('state', Mage_Sales_Model_Recurring_Profile::STATE_ACTIVE)
            ->addFieldToFilter('start_datetime',array('lteq' => Varien_Date::now(true)));

        /** @var Bitbull_BancaSellaPro_Helper_Data $_helper */
        $_helper = Mage::helper('bitbull_bancasellapro');
        $_helper->log('Cron per il pagamento dei reccuring profile. Ci sono '.count($recurringProfieles).' profili da analizzare');

        foreach($recurringProfieles as $recurringProfile){

            //metodo per deserializzare i dati all'interno del profilo
            $recurringProfieles->getResource()->unserializeFields($recurringProfile);

            //recupero il metodo di pagamento, è certo che è gestpay perché è uno dei criteri della query sopra
            $methodInstance = Mage::helper('payment')->getMethodInstance($recurringProfile->getMethodCode());
            $info = Mage::getModel('payment/info');

            $methodInstance->submitRecurringProfile($recurringProfile, $info);

        }
        echo 'end';
    }

} 