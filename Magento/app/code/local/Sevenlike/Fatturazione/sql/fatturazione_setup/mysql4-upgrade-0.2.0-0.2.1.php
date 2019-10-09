<?php
$setup = $this;
$setup->startSetup();


$setup->run("
ALTER TABLE {$this->getTable('sales_flat_invoice')} ADD COLUMN `tipo` varchar(255) DEFAULT 'ndc';
ALTER TABLE {$this->getTable('sales_flat_invoice_grid')} ADD COLUMN `tipo` varchar(255) DEFAULT 'ndc';
");

$setup->endSetup();