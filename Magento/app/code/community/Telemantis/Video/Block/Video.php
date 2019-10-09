<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Telemantis_Video_Block_Video extends Mage_Core_Block_Template
{
     public $_login;
     
     public $_width;
     
     public $_height;
     
     public $_strech;
     
     public $_autostart;
     
     public $_control;
     
     public $_controlbar;
      
     public $_flag=1; 
      
     protected function _construct()
    {
         echo "kela lele"; exit;
         $this->_login= Mage::getStoreConfig('video/video_group/login');  
         $this->_width  = Mage::getStoreConfig('video/video_group/width');  
         $this->_height = Mage::getStoreConfig('video/video_group/height');   
         $this->_strech = Mage::getStoreConfig('video/video_group/stretch'); 
         $this->_autostart = Mage::getStoreConfig('video/video_group/autostart'); 
         $this->_controls = Mage::getStoreConfig('video/video_group/controls');   
         $this->_controlbar = Mage::getStoreConfig('video/video_group/controlbar'); 
    
         $this->_autostart= $this->_autostart == 1 ? 'true' : 'false';
         $this->_controls= $this->_controls == 1 ? 'true' : 'false';
         $this->_controlbar= $this->_controlbar == 1 ? 'over' : 'none';
    
         $this->_flag=1;
    }
     
}

?>
