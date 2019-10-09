<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `sl_registro_corrispettivi` CHANGE COLUMN `reg_id` `reg_id` INT(11) NOT NULL AUTO_INCREMENT  ;
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 