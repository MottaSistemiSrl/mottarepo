<?php   
class MG_Megaforum_Block_Index extends Mage_Core_Block_Template{



    public function addToTopLink() {
			
			 $topBlock = $this->getParentBlock();
								
			 if(Mage::getSingleton('customer/session')->isLoggedIn()) {
			 
				if($topBlock) 
				{

					$topBlock->addLink($this->__('My Forum'),'megaforum/index/index/forum_id/0/',
										'megaforum',true,array(),10
									);
				}
			
		} else {

				$topBlock->addLink($this->__('Megaforum'),'customer/account/create/','megaforum',true,array(),10);
		
		}
			
		}
       
    public function getForumId() {
	
	 return Mage::app()->getRequest()->getParam('forum_id');
	
	}
	
	public function __construct()
    {
        parent::__construct();
		
		if (Mage::app()->getRequest()->getParam('forum_id')==0) {
		$forumStore = Mage::getModel('megaforum/forumstore')->getCollection()->addFieldToFilter("store_id",Mage::app()->getStore(true)->getId());
		
		$storeId = array(); 
		foreach($forumStore as $forumStores){ 
		array_push($storeId, $forumStores->getForumId()); 	}
		$collection = Mage::getModel('megaforum/forum')->getCollection()->addFieldToFilter("forum_id",array('in' => $storeId));  

        $this->setCollection($collection);
		
		} else {
		$collection = Mage::getModel('megaforum/topic')->getCollection()->addFieldToFilter("forum_id",Mage::app()->getRequest()->getParam('forum_id'));
        $this->setCollection($collection);	
		
		}
    }
 
    protected function _prepareLayout()
    {



        $forum = Mage::getModel('megaforum/forum')->getCollection()->addFieldToFilter("forum_id",Mage::app()->getRequest()->getParam('forum_id'))->getFirstItem();
        $topic = Mage::getModel('megaforum/topic')->getCollection()->addFieldToFilter("topic_id",Mage::app()->getRequest()->getParam('id'))->getFirstItem();
        $pageUrl = $this->helper('core/url')->getCurrentUrl();


        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbs->addCrumb('home', array('label'=>Mage::helper('cms')->__('Home'), 'title'=>Mage::helper('cms')->__('Home Page'), 'link'=>Mage::getBaseUrl()));
        $breadcrumbs->addCrumb('Community', array('label'=>'Community'));

        if ($forum->getForumName()) {
            $breadcrumbs->addCrumb('Community', array('label'=>'Community', 'title'=>'Community', 'link'=>Mage::getUrl("community")));
            $breadcrumbs->addCrumb('Forum', array('label'=>$forum->getForumName()));
        }

        $forumx = Mage::getModel('megaforum/forum')->load($topic->getForumId());
        $id = $this->getRequest()->getParam('id');
        $currentUser = Mage::getModel('customer/customer')->load($id);

        if ($topic->getTopicName() && !strpos($pageUrl, "profile")) {
            $breadcrumbs->addCrumb('Community', array('label'=>'Community', 'title'=>'Community', 'link'=>Mage::getUrl("community")));
            $breadcrumbs->addCrumb('Forum', array('label'=>$forumx->getForumName(), 'title'=>$forumx->getForumName(), 'link'=>Mage::getUrl("community/index/index/forum_id/". $forumx->getForumId())));
            $breadcrumbs->addCrumb('Topic', array('label'=>$topic->getTopicName()));
        }

        if ($currentUser->load($id)) {
            $breadcrumbs->addCrumb('Community', array('label'=>'Community', 'title'=>'Community', 'link'=>Mage::getUrl("community")));
            $breadcrumbs->addCrumb('Profilo', array('label'=>$currentUser->getSevenlikeNickname(), 'title'=>$currentUser->getSevenlikeNickname()));
        }

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

       // return parent::_prepareLayout();

	$headBlock = $this->getLayout()->getBlock('head');
		
        if ($headBlock) {
		
            $forum = Mage::getModel('megaforum/forum')->getCollection()->addFieldToFilter("forum_id",Mage::app()->getRequest()->getParam('forum_id'))->getFirstItem(); 
			
            $title = $forum->getForumName();
            if ($title) {
                $headBlock->setTitle($title);
            }
			
			$keyword = $forum->getMetaKey();

            if ($keyword) {
                $headBlock->setKeywords($keyword);
            } 
            $description = $forum->getMetaDesc();
			
            if ($description) {
                $headBlock->setDescription( ($description) );
            } 
						
			$topic = Mage::getModel('megaforum/topic')->getCollection()->addFieldToFilter("topic_id",Mage::app()->getRequest()->getParam('id'))->getFirstItem();
			
			$title = $topic->getTopicName();
            if ($title) {
                $headBlock->setTitle($title);
            }
			
            $keyword = $topic->getMetaKey();

            if ($keyword) {
                $headBlock->setKeywords($keyword);
            } 
            $description = $topic->getMetaDesc();
			
            if ($description) {
                $headBlock->setDescription( ($description) );
            } 
			
		}
        return $this;
		
   /* parent::_prepareLayout();

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
         */
    }
	
	public function getDefaultDirection(){
        return 'asc';
    }
    public function getAvailableOrders(){
	if (Mage::app()->getRequest()->getParam('forum_id')==0) {
        return array('created_at'=> 'Created Date','forum_id'=>'Forum ID'); }
	else {
		return array('created_at'=> 'Created Date','topic_id'=>'Topic ID');
	}

    }
    public function getSortBy(){
	if (Mage::app()->getRequest()->getParam('forum_id')==0) {
          return 'forum_id'; }
	else {
		return 'topic_id';
	}

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