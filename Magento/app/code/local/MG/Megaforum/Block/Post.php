<?php   
class MG_Megaforum_Block_Post extends Mage_Core_Block_Template
{
    public function __construct()
    {
        parent::__construct();
		
		$id = $this->getRequest()->getParam("id");
		$collection = Mage::getModel('megaforum/post')
            ->getCollection()
            ->addFieldToFilter("topic_id", $id)
            ->addFieldToFilter('post_id', array("nin"=>array($this->getFirstPost()->getPostId())));
        $this->setCollection($collection);

    }

    public function getFirstPost()
    {
        $id = $this->getRequest()->getParam("id");
        return Mage::getModel('megaforum/post')->getFirstPost($id);
    }
 
    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $toolbar = $this->getToolbarBlock();
 
        // called prepare sortable parameters
        $collection = $this->getCollection();
 
        // use sortable parameters
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }
        if ($sort = $this->getSortBy()) {
            $toolbar->setDefaultOrder($sort);
        }
        if ($dir = $this->getDefaultDirection()) {
            $toolbar->setDefaultDirection($dir);
        }
        $toolbar->setCollection($collection);
 
        $this->setChild('toolbar', $toolbar);
        $this->getCollection()->load();
        return $this;
    }
    public function getDefaultDirection(){
        return 'desc';
    }
    public function getAvailableOrders(){
        return array('posted_at'=> 'Posted Date','post_id'=>'ID');
    }
    public function getSortBy(){
          return 'post_id';
    }
    public function getToolbarBlock()
    {
        $block = $this->getLayout()->createBlock('megaforum/toolbar', microtime());
        return $block;
    }
    public function getMode()
    {
        return $this->getChild('toolbar')->getCurrentMode();
    }
 
    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }

}