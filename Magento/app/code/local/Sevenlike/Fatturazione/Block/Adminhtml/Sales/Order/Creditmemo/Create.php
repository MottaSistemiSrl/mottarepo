<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 06/09/13
 * Time: 17.42
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Block_Adminhtml_Sales_Order_Creditmemo_Create extends Mage_Adminhtml_Block_Sales_Order_Creditmemo_Create{
    public function getHeaderText()
    {
        if ($this->getCreditmemo()->getInvoice()) {
            if($this->getCreditmemo()->getTipo()==Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO){
                $testo = "Nuova Nota di Credito per Fattura n. ";
            }else{
                $testo = "Nuova Nota di Credito per per nota di consegna n. ";
            }
            $header = Mage::helper('sales')->__($testo.' #%s', $this->getCreditmemo()->getInvoice()->getIncrementId());
        }
        else {
            $header = Mage::helper('sales')->__('New Credit Memo for Order #%s', $this->getCreditmemo()->getOrder()->getRealOrderId());
        }

        return $header;
    }
}