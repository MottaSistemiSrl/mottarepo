<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 09/09/13
 * Time: 14.28
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Adminhtml_StampaController extends Mage_Adminhtml_Controller_Action{
    public function printInvoiceAction()
    {
        if ($invoiceId = $this->getRequest()->getParam('invoice_id')) {
            if ($invoice = Mage::getModel('sales/order_invoice')->load($invoiceId)) {
                if($invoice->getTipo()==Sevenlike_Fatturazione_Model_Tipodoc::FATTURA){
                    $pdf = Mage::getModel('fatturazione/pdf_fattura')->getPdf(array($invoice));
                    $this->_prepareDownloadResponse('fattura'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                    '.pdf', $pdf->render(), 'application/pdf');
                }else{
                    $pdf = Mage::getModel('fatturazione/pdf_notaconsegna')->getPdf(array($invoice));
                    $this->_prepareDownloadResponse('ndc'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                    '.pdf', $pdf->render(), 'application/pdf');
                }
            }
        }
        else {
            $this->_forward('noRoute');
        }
    }

    public function pdfinvoicesAction(){
        $invoicesIds = $this->getRequest()->getPost('invoice_ids');
        if (!empty($invoicesIds)) {
            $invoices = Mage::getResourceModel('sales/order_invoice_collection')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', array('in' => $invoicesIds))
                ->load();
            $toprint = array();
            foreach($invoices as $invoice){
                $tipo = $invoice->getTipo();
                if(!$tipo){
                    $tipo = Sevenlike_Fatturazione_Model_Tipodoc::NDC;
                }
                $toprint[$tipo][] = $invoice;
            }
            if(!empty($toprint[Sevenlike_Fatturazione_Model_Tipodoc::FATTURA])){
                if (!isset($pdf)){
                    $pdf = Mage::getModel('fatturazione/pdf_fattura')->getPdf($toprint[Sevenlike_Fatturazione_Model_Tipodoc::FATTURA]);
                } else {
                    $pages = Mage::getModel('fatturazione/pdf_fattura')->getPdf($toprint[Sevenlike_Fatturazione_Model_Tipodoc::FATTURA]);;
                    $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                }
            }
            if(!empty($toprint[Sevenlike_Fatturazione_Model_Tipodoc::NDC])){

                if (!isset($pdf)){
                    $pdf = Mage::getModel('fatturazione/pdf_notaconsegna')->getPdf($toprint[Sevenlike_Fatturazione_Model_Tipodoc::NDC]);
                } else {
                    $pages = Mage::getModel('fatturazione/pdf_notaconsegna')->getPdf($toprint[Sevenlike_Fatturazione_Model_Tipodoc::NDC]);;
                    $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                }
            }
            if (isset($pdf)){
                return $this->_prepareDownloadResponse('invoice'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                '.pdf', $pdf->render(), 'application/pdf');
            }
        }

        $this->_redirect('*/*/');
    }

    public function pdfcreditmemosAction(){
        $creditmemosIds = $this->getRequest()->getPost('creditmemo_ids');
        if (!empty($creditmemosIds)) {
            $invoices = Mage::getResourceModel('sales/order_creditmemo_collection')
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('entity_id', array('in' => $creditmemosIds))
                ->load();
            $toprint = array();
            foreach($invoices as $invoice){
                $tipo = $invoice->getTipo();
                if(!$tipo){
                    $tipo = Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO_NDC;
                }
                $toprint[$tipo][] = $invoice;
            }
            if(!empty($toprint[Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO])){
                if (!isset($pdf)){
                    $pdf = Mage::getModel('fatturazione/pdf_notacredito')->getPdf($toprint[Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO]);
                } else {
                    $pages = Mage::getModel('fatturazione/pdf_notacredito')->getPdf($toprint[Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO]);;
                    $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                }
            }
            if(!empty($toprint[Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO_NDC])){

                if (!isset($pdf)){
                    $pdf = Mage::getModel('fatturazione/pdf_notacreditondc')->getPdf($toprint[Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO_NDC]);
                } else {
                    $pages = Mage::getModel('fatturazione/pdf_notacreditondc')->getPdf($toprint[Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO_NDC]);;
                    $pdf->pages = array_merge ($pdf->pages, $pages->pages);
                }
            }

            if (isset($pdf)){
                return $this->_prepareDownloadResponse('creditmemo'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                '.pdf', $pdf->render(), 'application/pdf');
            }
        }
        $this->_redirect('*/*/');
    }

    public function printCreditmemoAction()
    {
        /** @see Mage_Adminhtml_Sales_Order_InvoiceController */
        if ($creditmemoId = $this->getRequest()->getParam('creditmemo_id')) {
            if ($creditmemo = Mage::getModel('sales/order_creditmemo')->load($creditmemoId)) {

                if($creditmemo->getTipo()==Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO){
                    $pdf = Mage::getModel('fatturazione/pdf_notacredito')->getPdf(array($creditmemo));
                    $this->_prepareDownloadResponse('nota_di_credito'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                    '.pdf', $pdf->render(), 'application/pdf');
                }else{
                    $pdf = Mage::getModel('fatturazione/pdf_notacreditondc')->getPdf(array($creditmemo));
                    $this->_prepareDownloadResponse('notacredito_ndc'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                    '.pdf', $pdf->render(), 'application/pdf');
                }
            }
        }
        else {
            $this->_forward('noRoute');
        }
    }

}