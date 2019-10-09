<?php
$setup = $this;
$setup->startSetup();

$setup->run("
	DROP TABLE IF EXISTS {$this->getTable('fatturazione/numerazione')};
	CREATE TABLE {$this->getTable('fatturazione/numerazione')} (
	`id` INTEGER(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`tipo_doc` VARCHAR(50) NOT NULL,
	`store_id` INTEGER(11) UNSIGNED NOT NULL,
	`anno_corrente` INTEGER(4) UNSIGNED NOT NULL,
	`ultimo_id` INTEGER(11) UNSIGNED NOT NULL,
	`ultimo_numero_generato` VARCHAR(255) NOT NULL,
    `created_at` datetime DEFAULT NULL,
	`updated_at` datetime DEFAULT NULL,
	PRIMARY KEY (`id`))
	ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$setup->endSetup();