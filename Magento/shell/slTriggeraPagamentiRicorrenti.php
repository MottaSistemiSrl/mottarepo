<?php
/**
 * Class     mirko-testing.php
 * @category ${NAMESPACE}
 * @package  ${NAMESPACE}
 * @author   Mirko Cesaro <mirko.cesaro@gmail.com>
 */


require('../app/Mage.php');
umask(0);
Mage::setIsDeveloperMode(true);
Mage::app();

$obs = Mage::getModel('bitbull_bancasellapro/observer');

$obs->chargeRecurringProfiles();