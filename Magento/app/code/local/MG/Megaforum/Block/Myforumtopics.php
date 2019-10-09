<?php   
class MG_Megaforum_Block_Myforumtopics extends Mage_Core_Block_Template{   

	public function __construct()
    {
        parent::__construct();

        $customerSession = Mage::getSingleton('customer/session');

		$collection = Mage::getModel('megaforum/topic')->getCollection();
        $this->setCollection($collection);

    }
 
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
 
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        //Dan Nistor - remove limit 5
        //$pager->setAvailableLimit(array(5=>5,10=>10,15=>15,'all'=>'all'));
        //davide
        //filter collection by user that create topic
        $customerSession = Mage::getSingleton('customer/session');
        $pager->setCollection($this->getCollection()->addFilter('created_by',$customerSession->getId()));
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }
 
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}