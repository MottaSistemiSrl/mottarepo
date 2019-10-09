<?php
require_once 'abstract.php';
class Sevenlike_Shell_CancelOrders extends Mage_Shell_Abstract
{
    public function run()
    {
        $coreResource = Mage::getSingleton('core/resource') ;
        $write = $coreResource->getConnection('core_write');
	$q = "select entity_id from sales_flat_order where state = 'canceled' and (total_canceled is null or base_total_canceled = 0)";
	$orders = $write->fetchCol($q);
	foreach($orders as $orderId){
		$o = Mage::getModel('sales/order')->load($orderId);
		$o->setState('processing',true);
		$o->cancel();
		$o->addStatusHistoryComment('cancellato automaticamente');
		$o->save();
	}	
     }
}

$shell = new Sevenlike_Shell_CancelOrders();
$shell->run();
