<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 06/09/13
 * Time: 17.42
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Block_Adminhtml_Sales_Order_Invoice_View extends Mage_Adminhtml_Block_Sales_Order_Invoice_View{
    public function getHeaderText()
    {
        if ($this->getInvoice()->getEmailSent()) {
            $emailSent = Mage::helper('sales')->__('the invoice email was sent');
        }
        else {
            $emailSent = Mage::helper('sales')->__('the invoice email is not sent');
        }
        if($this->getInvoice()->getTipo()==Sevenlike_Fatturazione_Model_Tipodoc::FATTURA){
            $testo = "Fattura";
        }else{
            $testo = "Ricevuta di pagamento";
        }
        return Mage::helper('sales')->__($testo.' n. %1$s | %2$s | %4$s (%3$s)', $this->getInvoice()->getIncrementId(), $this->getInvoice()->getStateName(), $emailSent, $this->formatDate($this->getInvoice()->getCreatedAtDate(), 'medium', true));
    }

    public function getPrintUrl(){
        return $this->getUrl('adminfatturazione/adminhtml_stampa/printInvoice', array(
            'invoice_id' => $this->getInvoice()->getId()
        ));
    }
}