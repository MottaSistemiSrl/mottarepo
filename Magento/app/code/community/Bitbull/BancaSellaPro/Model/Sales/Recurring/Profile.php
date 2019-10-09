<?php
 /**
 * Class     Profile.php
  * @category Bitbull
  * @package  Bitbull_BancaSellaPro
  * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
  */

class Bitbull_BancaSellaPro_Model_Sales_Recurring_Profile extends Mage_Sales_Model_Recurring_Profile{


    public function setNearestStartDatetime(Zend_Date $minAllowed = null)
    {
        $date = $minAllowed;
        //Aggiungiamo il controllo sul method code per non aggiornare la data d'inizio con bancasellapro
        if (!$date || ( $date->getTimestamp() < time() && $this->getMethodCode() != Bitbull_BancaSellaPro_Model_Gestpay::METHOD_CODE) ) {
            $date = new Zend_Date(time());
        }
        $this->setStartDatetime($date->toString(Varien_Date::DATETIME_INTERNAL_FORMAT));
        return $this;
    }
}