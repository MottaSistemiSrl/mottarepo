<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `sl_registro_corrispettivi` ADD COLUMN `provincia` VARCHAR(100) NULL;
ALTER TABLE `sl_registro_corrispettivi` ADD COLUMN `regione` VARCHAR(100) NULL;


SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 