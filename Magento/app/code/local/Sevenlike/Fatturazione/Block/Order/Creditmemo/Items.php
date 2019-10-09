<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 11/09/13
 * Time: 16.25
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Block_Order_Creditmemo_Items extends Mage_Sales_Block_Order_Creditmemo_Items{


    public function getPrintCreditmemoUrl($creditmemo)
    {
        $id= $creditmemo->getOrder()->getId().':'.$creditmemo->getId();
        $id = base64_encode($id);
        return Mage::getUrl('fatturazione/stampa/printCreditmemo', array('id' => $id));
    }

    public function getPrintAllCreditmemosUrl($order)
    {
        $id= $order->getId().':'.$order->getCustomerEmail();
        $id = base64_encode($id);
        return Mage::getUrl('fatturazione/stampa/printAllCreditmemo', array('id' => $id));
    }
}