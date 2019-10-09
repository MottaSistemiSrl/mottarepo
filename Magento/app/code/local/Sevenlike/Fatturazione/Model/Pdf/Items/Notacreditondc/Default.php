<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Mage
 * @package     Mage_Sales
 * @copyright   Copyright (c) 2012 Magento Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Sales Order Invoice Pdf default items renderer
 *
 * @category   Mage
 * @package    Mage_Sales
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Sevenlike_Fatturazione_Model_Pdf_Items_Notacreditondc_Default extends Mage_Sales_Model_Order_Pdf_Items_Abstract
{
    /**
     * Draw item line
     */
    public function draw()
    {
        $order  = $this->getOrder();
        $item   = $this->getItem();
        $pdf    = $this->getPdf();
        $page   = $this->getPage();
        $lines  = array();
        $impSconto  = Mage::helper('fatturazione')->getImponibileScontoPdf($item);
        $imponibile = $impSconto['imponibile'];
        $discount = $impSconto['sconto'];
        $presconto = $impSconto['presconto'];

        // draw Product name
        /*$lines[0] = array(array(
            'text' => Mage::helper('core/string')->str_split($item->getName(), 35, true, true),
            'feed' => 35,
        )); */


        $lines[0] = array(array(
            'text' => Mage::helper('core/string')->str_split($this->getItem()->getOrderItem()->getProduct()->getAttributeText('categoria_prodotto'), 20, true, true),
            'feed' => 30,
        ));
        // draw SKU
        $lines[0][] = array(
            'text'  => Mage::helper('core/string')->str_split($this->getSku($item), 17),
            'feed'  => 220,
            'align' => 'right'
        );



        // draw QTY
        $lines[0][] = array(
            'text'  => $item->getQty() * 1,
            'feed'  => 240,
            'font'  => 'bold',
            'align' => 'right',
        );

        // draw Total (ex)
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt($presconto),
            'feed'  => 300,
            'font'  => 'bold',
            'align' => 'right',
        );


        // draw Discount
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt(-$discount),
            'feed'  => 360,
            'font'  => 'bold',
            'align' => 'right'
        );



        // draw Total (ex)
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt($imponibile),
            'feed'  => 405,
            'font'  => 'bold',
            'align' => 'right',
        );


        $taxPercent = $item->getOrderItem()->getTaxPercent();
        $taxPercent = number_format($taxPercent,0,',','');
        $taxPercent = $taxPercent."%";
        $lines[0][] = array(
            'text'  => $taxPercent ,
            'feed'  => 450,
            'font'  => 'bold',
            'align' => 'right'
        );



        // draw Tax
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt($item->getTaxAmount()),
            'feed'  => 500,
            'font'  => 'bold',
            'align' => 'right'
        );


        // draw Total (inc)
        $subtotal = $item->getRowTotal() + $item->getTaxAmount() + $item->getHiddenTaxAmount()
            - $item->getDiscountAmount();
        $lines[0][] = array(
            'text'  => $order->formatPriceTxt($subtotal),
            'feed'  => 565,
            'font'  => 'bold',
            'align' => 'right'
        );

        // draw options
        $options = $this->getItemOptions();
        if ($options) {
            foreach ($options as $option) {
                // draw options label
                $lines[][] = array(
                    'text' => Mage::helper('core/string')->str_split(strip_tags($option['label']), 40, true, true),
                    'font' => 'italic',
                    'feed' => 35
                );

                // draw options value
                $_printValue = isset($option['print_value']) ? $option['print_value'] : strip_tags($option['value']);
                $lines[][] = array(
                    'text' => Mage::helper('core/string')->str_split($_printValue, 30, true, true),
                    'feed' => 40
                );
            }
        }

        $lineBlock = array(
            'lines'  => $lines,
            'height' => 20
        );

        $page = $pdf->drawLineBlocks($page, array($lineBlock), array('table_header' => true));
        $this->setPage($page);
    }
}
