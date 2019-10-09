<?php
/**
 * Class     Token.php
 *
 * @method string getToken()
 * @method Bitbull_BancaSellaPro_Model_Token setToken(string)
 * @method string getExpiryDate()
 * @method Bitbull_BancaSellaPro_Model_Token setExpiryDate(string)
 * @method int getOrderId()
 * @method Bitbull_BancaSellaPro_Model_Token setOrderId(int)
 * @method int getProfileId()
 * @method Bitbull_BancaSellaPro_Model_Token setProfileId(int)
 * @method int getCustomerId()
 * @method Bitbull_BancaSellaPro_Model_Token setCustomerId(int)
 *
 * @category Bitbull_Bancasellapro
 * @package  Bitbull
 * @author   Mirko Cesaro <mirko.cesaro@gmail.com>
 */

class Bitbull_BancaSellaPro_Model_Token extends Mage_Core_Model_Abstract{

    public function _construct()
    {
        $this->_init('bitbull_bancasellapro/token');
    }

    /**
     * Set the plofile inside the model
     * @param Mage_Payment_Model_Recurring_Profile $profile
     * @return $this
     */
    public function setProfile($profile){
        $this->setProfileId($profile->getId());
        $this->setCustomerId($profile->getCustomerId());
        return $this;
    }

    /**
     * Set the token information inside the model
     * @param string $token string with 16 characters
     * @param string $expiryMonth month in 2 digits
     * @param string $expiryYear year in 2 digits
     * @return $this
     */
    public function setTokenInfo($token, $expiryMonth, $expiryYear){
        $date = Mage::getModel('core/date');

        $stringDate = "20$expiryYear-$expiryMonth-01";
        $expiryDate = $date->date('Y-m-t',$stringDate);
        $this->setExpiryDate($expiryDate);
        $this->setToken($token);

        return $this;

    }

    /**
     * Method to check if the token is expiry
     * @return bool
     */
    public function isExpiry()
    {
        $now = strtotime(Varien_Date::now());
        if($now > strtotime($this->getExpiryDate())){
            return true;
        }
        return false;
    }

    /**
     * Method that return a valid token for the sended profile
     * @param Mage_Payment_Model_Recurring_Profile $profile
     * @return $this
     */
    public function getFirstValidTokenForProfile( Mage_Payment_Model_Recurring_Profile$profile)
    {
        return $this->getCollection()
            ->addProfileToFilter($profile)
            ->addValidDateFilter()
            ->getFirstItem();
    }

    /**
     * Return the first token for the sended profile
     * @param Mage_Payment_Model_Recurring_Profile $profile
     * @return $this
     */
    public function getFirstTokenForProfile(Mage_Payment_Model_Recurring_Profile $profile)
    {
        return $this->getCollection()
            ->addProfileToFilter($profile)
            ->getFirstItem();
    }

} 