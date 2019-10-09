<?php
$setup = $this;
$setup->startSetup();

$setup->run("
	ALTER TABLE `sl_numerazione_documenti` CHANGE COLUMN `created_at` `created_at` DATETIME NOT NULL  , CHANGE COLUMN `updated_at` `updated_at` TIMESTAMP NOT NULL  ;
	ALTER TABLE {$this->getTable('sales_flat_creditmemo')} ADD COLUMN `tipo` varchar(255) DEFAULT 'creditmemo_ndc';
ALTER TABLE {$this->getTable('sales_flat_creditmemo_grid')} ADD COLUMN `tipo` varchar(255) DEFAULT 'creditmemo_ndc';

");

$setup->endSetup();