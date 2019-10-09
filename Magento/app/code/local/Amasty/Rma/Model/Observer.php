<?php
/**
 * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
 */ 
class Amasty_Rma_Model_Observer 
{
    public function handleBlockOutput($observer)
    {
        /* @var $block Mage_Core_Block_Abstract */
        $block = $observer->getBlock();

        $transport = $observer->getTransport();

        if ($block instanceof Mage_Sales_Block_Order_History){
            $hlr = Mage::helper('amrma');
            if ($hlr->isModuleEnabled()){

                $html = $transport->getHtml();

                $dom = new DOMDocument();
                $dom->loadHTML($html);
                $domx = new DOMXPath($dom);


                $thead = $domx->evaluate("//table[@id='my-orders-table']/thead/tr");
                if($thead->item(0)){
                    $thead->item(0)->appendChild($dom->createElement('th', '&nbsp;'));
                }



                $entries = $domx->evaluate("//table[@id='my-orders-table']/tbody/*");

                foreach ($entries as $entry) {
                    $incrementId = null;

                    foreach ($entry->childNodes as $node) {
                        $incrementId = $node->nodeValue;
                        break;
                    }

                    $link = '&nbsp;';

                    $td = $dom->createElement('td');

                    if ($incrementId){
                        $order = Mage::getModel('sales/order')->load($incrementId, 'increment_id');
                        if ($order->getId() && $hlr->canCreateRma($order->getId())){
                            $a = $dom->createElement('a', $hlr->__("Return"));
                            $a->setAttribute("href", Mage::getUrl('amrma/customer/new',
                                    array(
                                        'order_id' => $order->getId()
                                    )
                            ));
                            $td->appendChild($a);
                        }
                    }

                    $entry->appendChild($td);
                }


                $html = $dom->saveHTML();

                $transport->setHtml($html);
            }
        }

    }
}
?>