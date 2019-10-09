<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 30/08/13
 * Time: 16.47
 * To change this template use File | Settings | File Templates.
 */
class Sevenlike_Fatturazione_Model_Observer
{
    public function addBillingHandle(Varien_Event_Observer $observer)
    {
        $_block = $observer->getBlock();
        /*get Block type*/

        if ($_block instanceof Mage_Checkout_Block_Onepage_Billing) {
            $_block->setTemplate('fatturazione/checkout/onepage/billing.phtml');
        }
    }

    public function beforeSaveInvoice($observer){
        $invoice = $observer->getEvent()->getInvoice();
        if(!$invoice->getIncrementId()){
            $tipo = $invoice->getTipo();
            if(!$tipo){
                $tipo = Sevenlike_Fatturazione_Model_Tipodoc::NDC;
            }
            if(Mage::getStoreConfig('fatturazione/general/indipendenti')==Sevenlike_Fatturazione_Model_Config_Indipendenti::WEBSITE){
                $store_id = $invoice->getStore()->getWebsiteId();

            }else{
                $store_id = $invoice->getStoreId();
            }
            $incId = Mage::getModel('fatturazione/numerazione')->getNextIncrementId($tipo,$store_id);
            $invoice->setIncrementId($incId);


        }

        return $this;

    }

    public function beforeSaveCreditmemo($observer){
        $creditmemo = $observer->getEvent()->getCreditmemo();
        if(!$creditmemo->getIncrementId()){
            $tipo = $creditmemo->getTipo();
            if(!$tipo){
                $tipo = Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO_NDC;
            }
            if(Mage::getStoreConfig('fatturazione/general/indipendenti')==Sevenlike_Fatturazione_Model_Config_Indipendenti::WEBSITE){
                $store_id = $creditmemo->getStore()->getWebsiteId();

            }else{
                $store_id = $creditmemo->getStoreId();
            }
            $incId = Mage::getModel('fatturazione/numerazione')->getNextIncrementId($tipo,$store_id);
            $creditmemo->setIncrementId($incId);

        }
        return $this;
    }

    public function aggiornaNumerazione($observer){
        $object = $observer->getDataObject();
        if($object->getCreatedAt() == $object->getUpdatedAt()){
            $tipo = $object->getTipo();
            if(!$tipo){
                if($object instanceof Mage_Sales_Model_Order_Invoice){
                    $tipo = Sevenlike_Fatturazione_Model_Tipodoc::NDC;
                }elseif($object instanceof Mage_Sales_Model_Order_Creditmemo){
                    $tipo = Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO_NDC;
                }
            }
            if(Mage::getStoreConfig('fatturazione/general/indipendenti')==Sevenlike_Fatturazione_Model_Config_Indipendenti::WEBSITE){
                $store_id = $object->getStore()->getWebsiteId();

            }else{
                $store_id =$object->getStoreId();
            }

            //modificato da davide per evitare azzeramento fatture con cambio anno
            Mage::getModel('fatturazione/numerazione')->getNextIncrementId($tipo,$store_id,true);
            //Mage::getModel('fatturazione/numerazione')->getNextIncrementId($tipo,$store_id);
        }
        return $this;
    }
}
