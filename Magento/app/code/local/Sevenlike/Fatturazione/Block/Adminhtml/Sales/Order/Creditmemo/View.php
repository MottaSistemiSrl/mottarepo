<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 06/09/13
 * Time: 17.42
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Block_Adminhtml_Sales_Order_Creditmemo_View extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_View{
    public function getHeaderText()
    {
        if ($this->getCreditmemo()->getEmailSent()) {
            $emailSent = Mage::helper('sales')->__('the credit memo email was sent');
        }
        else {
            $emailSent = Mage::helper('sales')->__('the credit memo email is not sent');
        }

        if($this->getCreditmemo()->getTipo()==Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO){
            $testo = "Nota di Credito";
        }else{
            $testo = "Nota di credito per nota di consegna";
        }
        return Mage::helper('sales')->__($testo.' n. %1$s | %3$s | %2$s (%4$s)', $this->getCreditmemo()->getIncrementId(), $this->formatDate($this->getCreditmemo()->getCreatedAtDate(), 'medium', true), $this->getCreditmemo()->getStateName(), $emailSent);

    }

    public function getPrintUrl(){
        return $this->getUrl('adminfatturazione/adminhtml_stampa/printCreditmemo', array(
            'creditmemo_id' => $this->getCreditmemo()->getId()
        ));
    }
}