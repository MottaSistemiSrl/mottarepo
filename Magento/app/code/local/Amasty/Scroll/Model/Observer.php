<?php
/**
 * @copyright   Copyright (c) 2009-2011 Amasty (http://www.amasty.com)
 */ 
class Amasty_Scroll_Model_Observer
{
    public function handleLayoutRender()
    {

    	if ('true' == (string)Mage::getConfig()->getNode('modules/Amasty_Shopby/active')) {
    		//return;
    	}

        $layout = Mage::getSingleton('core/layout');
        if (!$layout)
            return;
            
        $isAJAX = Mage::app()->getRequest()->getParam('is_ajax', false);
        $isAJAX = $isAJAX && Mage::app()->getRequest()->isXmlHttpRequest();
        if (!$isAJAX)
            return;
            
        $layout->removeOutputBlock('root');    
        Mage::app()->getFrontController()->getResponse()->setHeader('content-type', 'application/json');
            
		$page = Mage::helper('amscroll')->findProductList($layout);

		if (!$page) {
			return;
		}

        $container = $layout->createBlock('core/template', 'amscroll_container');
        $container->setData('page', $this->_removeAjaxParam($page->toHtml()));
        $container->setData('PPP','da');
        $layout->addOutputBlock('amscroll_container', 'toJson');
    }
    
    protected function _removeAjaxParam($html)
    {
        $html = str_replace('is_ajax=1&amp;', '', $html);
        $html = str_replace('is_ajax=1&',     '', $html);
        $html = str_replace('is_ajax=1',      '', $html);
        
        $html = str_replace('___SID=U', '', $html);

        return $html;
    }
}
