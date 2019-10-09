<?php
 /**
 * Class     upgrade-1.3.0-1.3.1.php
 * @author   Mirko Cesaro <mirko.cesaro@gmail.com>
 */

$installer = $this;
$installer->startSetup();
$_helper=Mage::helper('bitbull_bancasellapro/recurringprofile');

$status = Mage::getModel('sales/order_status');
$status->setStatus($_helper::STATUS_REFUND_TOTAL);
$status->setLabel('Rimborso pagamento iniziale');
$status->save();

$installer->endSetup();