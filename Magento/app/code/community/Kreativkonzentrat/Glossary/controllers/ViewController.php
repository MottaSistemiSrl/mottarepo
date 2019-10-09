<?php
/**
 * Class Kreativkonzentrat_Glossary_ViewController
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_ViewController extends Mage_Core_Controller_Front_Action
{

	/**
	 *
	 */
	public function indexAction () {
		$this->loadLayout();
		$this->renderLayout();
	}

	/**
	 *
	 */
	public function IdAction () {
		$glossary_id = $this->getRequest()->getParam('id');
		if ($glossary_id != null && $glossary_id != '') {
			$glossary = Mage::getModel('glossary/glossary')->load($glossary_id);
			$glossary->getData();
		} else {
			$resource      = Mage::getSingleton('core/resource');
			$read          = $resource->getConnection('core_read');
			$glossaryTable = $resource->getTableName('glossary');
			$select        = $read->select()->from($glossaryTable, array('glossary_id', 'title', 'glossary_content', 'status'))->where('status', 1)->order('title DESC');
			$glossary      = $read->fetchRow($select);
		}
		Mage::register('glossary', $glossary);
		$this->loadLayout();
		$this->renderLayout();
	}

	/**
	 *
	 */
	public function popupAction () {
		$glossary_id = $this->getRequest()->getParam('id');
		if ($glossary_id != null && $glossary_id != '') {
			$glossary = Mage::getModel('glossary/glossary')->load($glossary_id);
			$glossary->getData();
		} else {
			$resource      = Mage::getSingleton('core/resource');
			$read          = $resource->getConnection('core_read');
			$glossaryTable = $resource->getTableName('glossary');
			$select        = $read->select()->from($glossaryTable, array('glossary_id', 'title', 'glossary_content', 'status'))->where('status', 1)->order('title DESC');
			$glossary      = $read->fetchRow($select);
		}
		Mage::register('glossary', $glossary);
		$this->loadLayout();
		$this->renderLayout();
	}

	/**
	 *
	 */
	public function letterAction () {
        $this->loadLayout();
		//get letter param
		$params = array_keys($this->getRequest()->getParams());
		if ($params != null) {
			//get only entries with corresponding letter
			$collection = Mage::getModel('glossary/glossary')->getFilteredCollection()->addFieldToFilter('letter', $params[0])->setAlphabeticalOrder();
		} else {
			$collection = Mage::getModel('glossary/glossary')->getFilteredCollection();
		}


        $toolbar  = $this->getLayout()->getBlock('glossary_toolbar');
        $toolbar->addOrderToAvailableOrders('title', 'Title')
            ->setOrder('title')
            ->setDefaultOrder('title')
            ->setCollection($collection);
        $pager = $this->getLayout()->getBlock('product_list_toolbar_pager');
        $limits = $toolbar->getAvailableLimit();
        $pagerLimit = array();
        foreach ($limits as $_limit=>$value) {
            $pagerLimit[] = (int)$value;
        }
        $pager->setAvailableLimit($pagerLimit);
        $pager->setCollection($collection);

		Mage::register('glossary', $collection);
		//$this->loadLayout();
		$this->renderLayout();
	}

}