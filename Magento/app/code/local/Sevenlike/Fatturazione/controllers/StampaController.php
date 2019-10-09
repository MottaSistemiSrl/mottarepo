<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 09/09/13
 * Time: 14.28
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_StampaController extends Mage_Core_Controller_Front_Action{

    public function printInvoiceAction()
    {
        if ($invoiceId = $this->getRequest()->getParam('id')) {
            $id = base64_decode($invoiceId);
            $id = explode(':',$id);
            if(count($id)!=2){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            $orderId  = array_shift($id);
            $invoiceId = array_shift($id);
            if(!$orderId || !$invoiceId){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            $invoice = Mage::getModel('sales/order_invoice')->load($invoiceId);
            if(!$invoice->getId()){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            if($invoice->getOrder()->getId()!= $orderId){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }


            if($invoice->getTipo()==Sevenlike_Fatturazione_Model_Tipodoc::FATTURA){
                $pdf = Mage::getModel('fatturazione/pdf_fattura')->getPdf(array($invoice));
                $this->_prepareDownloadResponse('fattura'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                '.pdf', $pdf->render(), 'application/pdf');
            }else{
                $pdf = Mage::getModel('fatturazione/pdf_notaconsegna')->getPdf(array($invoice));
                $this->_prepareDownloadResponse('ricevuta'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                '.pdf', $pdf->render(), 'application/pdf');
            }
        }else{

            Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
            $this->_redirectReferer();
            return;
        }

    }

    public function printAllInvoiceAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $id = base64_decode($id);
            $id = explode(':',$id);
            if(count($id)!=2){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            $orderId  = array_shift($id);
            $email = array_shift($id);
            if(!$orderId || !$email){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }


            $order = Mage::getModel('sales/order')->load($orderId);
            if(!$order->getId()){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            if($order->getCustomerEmail()!= $email){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }

            $invoices = $order->getInvoiceCollection();
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

        }else{
            Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
            $this->_redirectReferer();
            return;
        }

    }

    public function printCreditmemoAction()
    {
        if ($creditmemoId = $this->getRequest()->getParam('id')) {
            $id = base64_decode($creditmemoId);
            $id = explode(':',$id);
            if(count($id)!=2){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            $orderId  = array_shift($id);
            $creditmemoId = array_shift($id);
            if(!$orderId || !$creditmemoId){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            $creditmemo = Mage::getModel('sales/order_creditmemo')->load($creditmemoId);
            if(!$creditmemo->getId()){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            if($creditmemo->getOrder()->getId()!= $orderId){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }


            if($creditmemo->getTipo()==Sevenlike_Fatturazione_Model_Tipodoc::CREDITMEMO){
                $pdf = Mage::getModel('fatturazione/pdf_notacredito')->getPdf(array($creditmemo));
                $this->_prepareDownloadResponse('nota_di_credito'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                '.pdf', $pdf->render(), 'application/pdf');
            }else{
                $pdf = Mage::getModel('fatturazione/pdf_notacreditondc')->getPdf(array($creditmemo));
                $this->_prepareDownloadResponse('notacredito_ndc'.Mage::getSingleton('core/date')->date('Y-m-d_H-i-s').
                '.pdf', $pdf->render(), 'application/pdf');
            }

        }else{
            Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
            $this->_redirectReferer();
            return;
        }

    }

    public function printAllCreditmemoAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $id = base64_decode($id);
            $id = explode(':',$id);
            if(count($id)!=2){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            $orderId  = array_shift($id);
            $email = array_shift($id);
            if(!$orderId || !$email){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }


            $order = Mage::getModel('sales/order')->load($orderId);
            if(!$order->getId()){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }
            if($order->getCustomerEmail()!= $email){
                Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
                $this->_redirectReferer();
                return;
            }

            $invoices = $order->getCreditmemosCollection();
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

        }else{
            Mage::getSingleton('customer/session')->addError(MAge::helper('fatturazione')->__('Documento non disponibile'));
            $this->_redirectReferer();
            return;
        }

    }
}