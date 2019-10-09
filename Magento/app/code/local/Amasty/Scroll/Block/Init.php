<?php
class Amasty_Scroll_Block_Init extends Mage_Core_Block_Template
{
    protected function _prepareLayout() 
    {
        return $this;
    }

    public function getTotalPages()
    {
    	$layout = $this->getLayout();
    	
    	$page = Mage::helper('amscroll')->findProductList($layout);
		if (!$page) {
			return 0;
		}
		
		return intval(Mage::getStoreConfig('catalog/frontend/' . $page->getMode() . '_per_page'));
    }
}