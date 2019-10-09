<?php

/**
* Setubridge Technolabs
* http://www.setubridge.com/
* @author SetuBridge
* @license http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
**/
?>
<?php
class Sbridge_Moreview_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/moreview?id=15 
    	 *  or
    	 * http://site.com/moreview/id/15 	
    	 */
    	/* 
		$moreview_id = $this->getRequest()->getParam('id');

  		if($moreview_id != null && $moreview_id != '')	{
			$moreview = Mage::getModel('moreview/moreview')->load($moreview_id)->getData();
		} else {
			$moreview = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($moreview == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$moreviewTable = $resource->getTableName('moreview');
			
			$select = $read->select()
			   ->from($moreviewTable,array('moreview_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$moreview = $read->fetchRow($select);
		}
		Mage::register('moreview', $moreview);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}