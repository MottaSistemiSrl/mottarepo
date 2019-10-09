<?php

/**
 * @author Amasty Team
 * @copyright Copyright (c) 2010-2012 Amasty (http://www.amasty.com)
 */
class Amasty_Scroll_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getModuleConfig($key)
    {
    	return Mage::getStoreConfig('amscroll/' . $key);    		
    }
    
    public function findProductList($layout)
    {

        $page = $layout->getBlock('product_list');
        
        if (!$page){
            $page = $layout->getBlock('search_result_list');
        }
        
        if (!$page) {
        	$page = $layout->getBlock('catalogsearch_advanced_result');
        }
        if(!$page){
            $page = $layout->getBlock('blog');
        }

        if(!$page){
            $page = $layout->getBlock('megaforum_post');
        }

        if(!$page){
            $page = $layout->getBlock('megaforum_index');
        }

        if(!$page){
            $page = $layout->getBlock('cat');
        }

        //Mage::log(print_r($page,true));
        return $page;
    }
    
    public function isEnabled()
    {
        if ($this->getModuleConfig('general/loading') == 'none'){
            return false;
        }

    	$routes = array(
    		'catalog',
    		'catalogsearch',
    		'tag',
    		'amshopby',
            'blog',
            'megaforum'
        );

        return in_array(Mage::app()->getRequest()->getRouteName(), $routes);
    }
}