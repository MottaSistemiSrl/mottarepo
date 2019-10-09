<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `sl_registro_corrispettivi` ADD COLUMN `discount_amount` DECIMAL(10,4) NULL;
ALTER TABLE `sl_corrispettivi_flat` ADD COLUMN `discount_amount` DECIMAL(10,4) NULL;



SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 