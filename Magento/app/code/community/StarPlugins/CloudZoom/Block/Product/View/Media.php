<?php
/**
 * Star Plugins Cloud Zoom - Magento Extension
 * @package     StarPlugins_CloudZoom
 * @category    StarPlugins
 * @copyright   Copyright 2013 Star Plugins. (http://www.starplugins.com)
 * @version:    1.0
 */

class StarPlugins_CloudZoom_Block_Product_View_Media extends Mage_Catalog_Block_Product_View_Media
{
    /** @var StarPlugins_CloudZoom_Helper_Data */
    protected $_helper;
    /**
     * Retrieve extension helper
     *
     * @return StarPlugins_CloudZoom_Helper_Data
     */
    public function getLocalHelper()
    {
        if (is_null($this->_helper)) {
            $this->_helper = Mage::helper('starplugins_cloudzoom');
        }
        return $this->_helper;
    }
    /**
     * @return StarPlugins_CloudZoom_Block_Product_View_Media
     */
    protected function _beforeToHtml(){
        if ($this->getLocalHelper()->getConfigFlag('enabled')) {
            $this->setTemplate('starplugins/cloudzoom/catalog/product/view/media.phtml');
        }
        return $this;
    }
}
