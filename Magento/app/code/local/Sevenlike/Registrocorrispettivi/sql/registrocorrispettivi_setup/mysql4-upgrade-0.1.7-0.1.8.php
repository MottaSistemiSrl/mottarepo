<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
ALTER TABLE `sl_corrispettivi_flat` ADD COLUMN `metodo_pagamento` VARCHAR(100) NULL;
ALTER TABLE `sl_corrispettivi_flat` ADD COLUMN `label_pagamento` VARCHAR(100) NULL  AFTER `metodo_pagamento` ;


SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 