<?php
/**
* @author Amasty Team
* @copyright Amasty
* @package Amasty_Orderattach
*/

$this->startSetup();

$this->run("
    UPDATE `{$this->getTable('amorderattach/field')}` SET `code`=`fieldname`;

    ALTER TABLE `{$this->getTable('amorderattach/field')}` DROP `fieldname`;
");

$this->endSetup();
