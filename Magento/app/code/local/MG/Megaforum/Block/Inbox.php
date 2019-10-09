<?php   
class MG_Megaforum_Block_Inbox extends Mage_Core_Block_Template{  

    public function __construct()
    {
        parent::__construct();
		
	$customerData = Mage::getSingleton('customer/session')->getCustomer();
	$customerName = $customerData->getId();
	$collection = Mage::getModel('megaforum/privatemsg')->getCollection()->addFieldToFilter("sent_to",$customerName)->setOrder('privatemsg_id', 'DESC');
        $this->setCollection($collection);	

    }
 
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
 
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        //$pager->setAvailableLimit(array(5=>5,10=>10,15=>15,'all'=>'all'));
        //remove limit by Davide
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }
 
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}