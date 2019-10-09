<?php
$installer = $this;
$installer->startSetup();
$sql=<<<SQLTEXT
CREATE TABLE `sl_registro_corrispettivi` (
  `reg_id` int(11) NOT NULL,
  `increment_id` varchar(45) NOT NULL,
  `tipo` varchar(45) NOT NULL,
  `store_id` int(11) NOT NULL,
  `data_comp` date NOT NULL,
  `imponibile` decimal(10,4) NOT NULL,
  `iva` decimal(10,4) NOT NULL,
  `aliquota` decimal(10,4) NOT NULL,
  `valuta` varchar(45) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`reg_id`),
  UNIQUE KEY `reg_id_UNIQUE` (`reg_id`),
  KEY `increment_id` (`increment_id`),
  KEY `data_comp` (`data_comp`,`aliquota`,`valuta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
SQLTEXT;

$installer->run($sql);
//demo 
//Mage::getModel('core/url_rewrite')->setId(null);
//demo 
$installer->endSetup();
	 