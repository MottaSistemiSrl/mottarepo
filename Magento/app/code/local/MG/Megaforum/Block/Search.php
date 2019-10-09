<?php   
class MG_Megaforum_Block_Search extends Mage_Core_Block_Template{   

	public function __construct()
    {
        parent::__construct();
		
		$query = $_POST['query']; 
	  
		$resource = Mage::getSingleton('core/resource');
		$readConnection = $resource->getConnection('core_read');
		$topic_table = $resource->getTableName('megaforum/topic');
		$post_table = $resource->getTableName('megaforum/post');

	/*	$result = "SELECT tabkey.topic_id, ".$topic_table.".topic_name, ".$topic_table.".message, ".$post_table.".post_text FROM (SELECT ".$topic_table.".topic_id FROM ".$topic_table." UNION SELECT ".$post_table.".topic_id FROM ".$post_table.") as tabkey LEFT JOIN  ".$topic_table." on tabkey.topic_id = ".$topic_table.".topic_id LEFT JOIN ".$post_table." on tabkey.topic_id = ".$post_table.".topic_id"." WHERE (topic_name LIKE '%".$query."%' OR message LIKE '%".$query."%' OR post_text LIKE '%".$query."%')"; */

		$result = "SELECT tabkey.topic_id FROM (SELECT ".$topic_table.".topic_id FROM ".$topic_table." UNION SELECT ".$post_table.".topic_id FROM ".$post_table.") as tabkey LEFT JOIN  ".$topic_table." on tabkey.topic_id = ".$topic_table.".topic_id LEFT JOIN ".$post_table." on tabkey.topic_id = ".$post_table.".topic_id"." WHERE (topic_name LIKE '%".$query."%' OR message LIKE '%".$query."%' OR post_text LIKE '%".$query."%')"; 
		
		$select = $readConnection->fetchAll($result);

		$topicCollection = Mage::getModel('megaforum/topic')->getCollection();	
		
	    if($select){
		
			$topicCollection->addFieldToFilter('topic_id', array('in' => array($select))); 
			$this->setCollection($topicCollection);	
			
		} else{
		
			$topicCollection->addFieldToFilter('topic_id', 0); 
			$this->setCollection($topicCollection);
		}				
		
    /*    $select = $readConnection->fetchAll($result);
			
		$collection = new Varien_Data_Collection();
		
		foreach($select as $selects)
		{
			$selectObj = new Varien_Object();
			$selectObj->setData($selects);					
			$collection->addItem($selectObj);			
		}		
        $this->setCollection($collection);		*/
		
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
        return 'asc';
    }
    public function getAvailableOrders(){
        return array('created_at'=> 'Created Date','topic_id'=>'Topic ID');
    }
    public function getSortBy(){
          return 'topic_id';
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