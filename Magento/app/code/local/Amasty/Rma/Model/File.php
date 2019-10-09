<?php
/**
 * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
 */ 
class Amasty_Rma_Model_File extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        $this->_init('amrma/file');
    }
   
    
    public function getHref(){
        return Mage::getUrl('amrma/customer/download', array('file' => $this->getFile()));
    }
    
    public static function getUploadPath($file)
    {
        return Mage::getBaseDir('media') . DS . 'amasty' . DS .'amrma' . DS . 'comments_upload'. DS . $file;
    }
}