<?php
 /**
  * Class     Collection.php
  * @category Bitbull_Bancasellapro
  * @package  Bitbull
  * @author   Mirko Cesaro <mirko.cesaro@gmail.com>
  */

class Bitbull_BancaSellaPro_Model_Resource_Token_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
        $this->_init('bitbull_bancasellapro/token');
    }


    public function addProfileToFilter(Mage_Payment_Model_Recurring_Profile $profile){
        return $this->addFieldToFilter('profile_id',array('eq'=>$profile->getId()));
    }

    public function addValidDateFilter(){
        return $this->addFieldToFilter('expiry_date',array('gteq' => Varien_Date::now(true)));
    }
} 