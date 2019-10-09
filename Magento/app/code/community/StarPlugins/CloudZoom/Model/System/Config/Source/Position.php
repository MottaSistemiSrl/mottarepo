<?php
/**
 * Star Plugins Cloud Zoom - Magento Extension
 * @package     StarPlugins_CloudZoom
 * @category    StarPlugins
 * @copyright   Copyright 2013 Star Plugins. (http://www.starplugins.com)
 * @version:    1.0
 */

class StarPlugins_CloudZoom_Model_System_Config_Source_Position
{
    /** @var StarPlugins_CloudZoom_Helper_Data */
    protected $_helper;

    /**
     * @return StarPlugins_CloudZoom_Helper_Data
     */
    public function getHelper()
    {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::helper('starplugins_cloudzoom');
        }
        return $this->_helper;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $helper = $this->getHelper();
        return array(
            'top' => $helper->__('Top'),
            'bottom' => $helper->__('Bottom')
        );
    }
}
