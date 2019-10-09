<?php
/**
 * Class     Newtoken.php
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class Bitbull_BancaSellaPro_Block_Tokenization_Newtoken extends Mage_Core_Block_Template
{

    protected $_method, $_order;


    /**
     * Retrieve credit card expire years
     *
     * @return array
     */
    public function getCcYears()
    {
        $years = $this->getData('cc_years');
        if (is_null($years)) {
            $years = Mage::helper('bitbull_bancasellapro')->getYears();
            $this->setData('cc_years', $years);
        }
        return $years;
    }

    /**
     * Retrieve credit card expire months
     *
     * @return array
     */
    public function getCcMonths()
    {
        $months = $this->getData('cc_months');
        if (is_null($months)) {

            $months = Mage::helper('bitbull_bancasellapro')->getMonths();
            $this->setData('cc_months', $months);
        }
        return $months;
    }

    protected function _construct()
    {
        parent::_construct();

        $profile = Mage::registry('current_recurring_profile');

        /** @var Bitbull_BancaSellaPro_Helper_Recurringprofile $recurringHelper */
        $recurringHelper = Mage::helper('bitbull_bancasellapro/recurringprofile');

        /** @var Bitbull_BancaSellaPro_Model_Gestpay $method */
        $method = Mage::helper('payment')->getMethodInstance($profile->getMethodCode());

        $order = $method->createOrderToNewToken($profile);

        $this->_order = $order;

        Mage::register('bitbull_current_order', $order);

    }

    public function getMethodCode(){
        $profile = Mage::registry('current_recurring_profile');
        return $profile->getMethodCode();
    }

    /**
     * Return an instance of Bancasella payment method
     * @return false|Mage_Payment_Model_Method_Abstract
     */
    public function getMethod(){
        if($this->_method==null){
            $profile = Mage::registry('current_recurring_profile');
            $this->_method = Mage::helper('payment')->getMethodInstance($profile->getMethodCode());
        }
        return $this->_method;
    }

    public function getEncryptString(){
        /** @var Bitbull_BancaSellaPro_Helper_Crypt $helper */
        $helper =Mage::helper('bitbull_bancasellapro/crypt');
        return $helper->getEncryptStringByOrderWitTokenRequest($this->_order);
    }

    public function getSuccessRedirect(){
        return Mage::getUrl('bancasellapro/tokenization/result',array('_secure' => Mage::app()->getStore()->isCurrentlySecure()));
    }

    public function getDisableProfileRedirect(){
        return Mage::getUrl('bancasellapro/tokenization/disable',
            array(
                '_secure' => Mage::app()->getStore()->isCurrentlySecure(),
                'profile'=> Mage::registry('current_recurring_profile')->getId()
            ));
    }

} 