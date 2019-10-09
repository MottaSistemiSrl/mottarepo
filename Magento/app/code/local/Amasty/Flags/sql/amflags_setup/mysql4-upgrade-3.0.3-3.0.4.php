<?php
/**
* @author Amasty Team
* @copyright Copyright (c) Amasty (http://www.amasty.com/)
* @package Amasty_Flags
*/
$this->startSetup();
$this->run(" ALTER TABLE `{$this->getTable('amflags/flag')}` ADD `apply_payment` TEXT NOT NULL ; ");
$this->endSetup();