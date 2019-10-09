<?php
$installer = $this;
$installer->startSetup();
$installer->run("
-- DROP TABLE IF EXISTS {$this->getTable('glossary')};
CREATE TABLE {$this->getTable('glossary')} (
	`glossary_id` int(11) unsigned NOT NULL auto_increment,
	`title` varchar(255) NOT NULL default '',
	`metadescription` varchar(255) NOT NULL default '',
	`metakeywords` varchar(255) NOT NULL default '',
	`filename` varchar(255) NOT NULL default '',
	`letter` varchar(255) NOT NULL default '',
	`glossary_content` text NOT NULL default '',
	`status` smallint(6) NOT NULL default '0',
	`created_time` datetime NULL,
	`update_time` datetime NULL,
	PRIMARY KEY (`glossary_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Glossary entries';

-- DROP TABLE IF EXISTS {$this->getTable('glossary_store')};
CREATE TABLE {$this->getTable('glossary_store')} (
	`glossary_id` int(11) unsigned NOT NULL,
	`store_id` smallint(5) unsigned NOT NULL,
	PRIMARY KEY (`glossary_id`,`store_id`),
	CONSTRAINT `FK_GLOSSARY_STORE_TO_GLOSSARY` FOREIGN KEY (`glossary_id`) REFERENCES `{$this->getTable('glossary')}` (`glossary_id`) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT `FK_GLOSSARY_STORE_TO_STORE` FOREIGN KEY (`store_id`) REFERENCES `{$this->getTable('core/store')}` (`store_id`) ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Glossary entries to stores';
");
$installer->endSetup();