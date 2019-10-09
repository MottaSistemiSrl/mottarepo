<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 09/09/13
 * Time: 14.41
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Model_Pdf_Notaconsegna extends Mage_Sales_Model_Order_Pdf_Invoice{
    protected function _drawHeader(Zend_Pdf_Page $page)
    {
        /* Add table head */
        $this->_setFontRegular($page, 10);
        $page->setFillColor(new Zend_Pdf_Color_RGB(0.93, 0.92, 0.92));
        $page->setLineColor(new Zend_Pdf_Color_GrayScale(0.5));
        $page->setLineWidth(0.5);
        $page->drawRectangle(25, $this->y, 570, $this->y -15);
        $this->y -= 10;
        $page->setFillColor(new Zend_Pdf_Color_RGB(0, 0, 0));

        //columns headers
        /*$lines[0][] = array(
            'text' => Mage::helper('sales')->__('Products'),
            'feed' => 35
        );*/

        $lines[0][] = array(
            'text' => Mage::helper('sales')->__('Categoria Prodotto'),
            'feed' => 30
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('SKU'),
            'feed'  => 220,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Qty'),
            'feed'  => 245,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Imp. presconto'),
            'feed'  => 320,
            'align' => 'right'
        );
        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Discount'),
            'feed'  => 360,
            'align' => 'right'
        );


        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Imponibile'),
            'feed'  => 415,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Aliquota'),
            'feed'  => 460,
            'align' => 'right'
        );

        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Tax'),
            'feed'  => 495,
            'align' => 'right'
        );



        $lines[0][] = array(
            'text'  => Mage::helper('sales')->__('Totale'),
            'feed'  => 565,
            'align' => 'right'
        );

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 5
        );

        $this->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $page->setFillColor(new Zend_Pdf_Color_GrayScale(0));
        $this->y -= 20;
    }

    /**
     * Return PDF document
     *
     * @param  array $invoices
     * @return Zend_Pdf
     */
    public function getPdf($invoices = array())
    {
        $this->_beforeGetPdf();
        $this->_initRenderer('ndc');


        $pdf = new Zend_Pdf();
        $this->_setPdf($pdf);
        $style = new Zend_Pdf_Style();
        $this->_setFontBold($style, 10);
        $currYear = date('Y');
        foreach ($invoices as $invoice) {
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->emulate($invoice->getStoreId());
                Mage::app()->setCurrentStore($invoice->getStoreId());
            }
            $page  = $this->newPage();
            $order = $invoice->getOrder();
            /* Add image */
            $this->insertLogo($page, $invoice->getStore());
            /* Add address */
            $this->insertAddress($page, $invoice->getStore());
            /* Add head */
            $this->insertOrder(
                $page,
                $order,
                Mage::getStoreConfigFlag(self::XML_PATH_SALES_PDF_INVOICE_PUT_ORDER_ID, $order->getStoreId())
            );
            /* Add document text and number */
            $this->insertDocumentNumber(
                $page,
                Mage::helper('sales')->__('Ricevuta di pagamento n. ') .  str_replace('/' .$currYear,'',$invoice->getIncrementId()) .' del '.Mage::helper('core')->formatDate(
                    $invoice->getCreatedAtStoreDate(), 'medium', false
                )
            );
            /* Add table */
            $this->_drawHeader($page);
            /* Add body */
            foreach ($invoice->getAllItems() as $item){
                if ($item->getOrderItem()->getParentItem()) {
                    continue;
                }
                /* Draw item */
                $this->_drawItem($item, $page, $order);
                $page = end($pdf->pages);
            }
            /* Add totals */
            $this->insertTotals($page, $invoice);
            if ($invoice->getStoreId()) {
                Mage::app()->getLocale()->revert();
            }
        }
        $this->_afterGetPdf();
        return $pdf;
    }

    protected function _getTotalsList($source)
    {
        $totals = Mage::getConfig()->getNode('global/pdf/totals_ndc')->asArray();
        usort($totals, array($this, '_sortTotalsList'));
        $totalModels = array();
        foreach ($totals as $index => $totalInfo) {
            if (!empty($totalInfo['model'])) {
                $totalModel = Mage::getModel($totalInfo['model']);
                if ($totalModel instanceof Mage_Sales_Model_Order_Pdf_Total_Default) {
                    $totalInfo['model'] = $totalModel;
                } else {
                    Mage::throwException(
                        Mage::helper('sales')->__('PDF total model should extend Mage_Sales_Model_Order_Pdf_Total_Default')
                    );
                }
            } else {
                $totalModel = Mage::getModel($this->_defaultTotalModel);
            }
            $totalModel->setData($totalInfo);
            $totalModels[] = $totalModel;
        }

        return $totalModels;
    }
}