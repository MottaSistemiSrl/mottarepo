<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `sl_registro_corrispettivi` ADD COLUMN `imponibile_excl_tax` DECIMAL(10,4) NOT NULL  AFTER `iva` ;

SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 