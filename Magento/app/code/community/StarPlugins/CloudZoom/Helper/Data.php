<?php
/**
 * Cloud Image Zoom - Magento Extension
 *
 * @package     CloudZoom
 * @category    StarPlugins
 * @copyright   Copyright 2013 Star Plugins. (http://www.starplugins.com)
 * @version:    1.0
 */

class StarPlugins_CloudZoom_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * @param string $node
     * @return mixed
     */
    public function getConfigData($node)
    {
        return Mage::getStoreConfig(sprintf('catalog/starplugins_cloudzoom/%s', $node));
    }

    /**
     * @param string $node
     * @return bool
     */
    public function getConfigFlag($node)
    {
        return (bool) Mage::getStoreConfig(sprintf('catalog/starplugins_cloudzoom/%s', $node));
    }
}
