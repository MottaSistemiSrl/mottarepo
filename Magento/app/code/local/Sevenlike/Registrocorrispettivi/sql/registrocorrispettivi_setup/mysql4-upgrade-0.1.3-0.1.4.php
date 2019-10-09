<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT




CREATE  TABLE `sl_corrispettivi_flat` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `cod_paese` VARCHAR(45) NOT NULL DEFAULT 'IT',
  `data_comp` DATE NOT NULL ,
  `tipo_doc` VARCHAR(45) NOT NULL ,
  `n_doc` VARCHAR(45) NOT NULL ,
  `descrizione` VARCHAR(255) NULL ,
  `aliquota` DECIMAL(10,4) NOT NULL ,
  `valuta` VARCHAR(45) NOT NULL ,
  `fattore_valuta` DECIMAL(10,4) NOT NULL DEFAULT 1 ,
  `importo` DECIMAL(10,4) NOT NULL ,
  `p_iva` VARCHAR(45) ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;

SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 