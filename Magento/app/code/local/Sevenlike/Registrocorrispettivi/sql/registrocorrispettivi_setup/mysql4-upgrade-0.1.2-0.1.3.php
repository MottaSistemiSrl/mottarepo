<?php
$installer = $this;
$installer->startSetup();
/*$sql=<<<SQLTEXT
ALTER TABLE `sales_flat_creditmemo` ADD COLUMN `add_corrispettivo` CHAR NULL;
ALTER TABLE `sales_flat_invoice` ADD COLUMN `add_corrispettivo` CHAR NULL;
ALTER TABLE `sales_flat_shipment` ADD COLUMN `add_corrispettivo` CHAR NULL;
SQLTEXT;

$installer->run($sql);*/
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 