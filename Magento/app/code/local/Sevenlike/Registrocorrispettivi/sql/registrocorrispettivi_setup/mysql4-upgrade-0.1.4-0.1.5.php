<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `sl_corrispettivi_flat` ADD COLUMN `iva` DECIMAL(10,4) NOT NULL  AFTER `p_iva` , ADD COLUMN `importo_netto` DECIMAL(10,4) NOT NULL  AFTER `iva` ;


SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 