<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `sl_registro_corrispettivi` ADD COLUMN `metodo_pagamento` VARCHAR(100) NULL  AFTER `increment_id` ;
ALTER TABLE `sl_registro_corrispettivi` ADD COLUMN `label_pagamento` VARCHAR(100) NULL  AFTER `metodo_pagamento` ;
ALTER TABLE `sl_corrispettivi_flat` CHANGE COLUMN `descrizione` `descrizione` TEXT CHARACTER SET 'utf8' COLLATE 'utf8_unicode_ci' NULL DEFAULT NULL  ;



SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 