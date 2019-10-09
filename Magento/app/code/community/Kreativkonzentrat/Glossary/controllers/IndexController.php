<?php
/**
 * Class Kreativkonzentrat_Glossary_IndexController
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_IndexController extends Mage_Core_Controller_Front_Action
{

	/**
	 *
	 */
	public function indexAction () {
		$this->loadLayout();
		$glossaryId = $this->getRequest()->getParam('id');
		if ($glossaryId != null && $glossaryId != '') {
			$glossary = Mage::getModel('glossary/glossary')->load($glossaryId)->getData();
		} else {
			$glossary = Mage::getModel('glossary/glossary')->getFilteredCollection();
			$toolbar  = $this->getLayout()->getBlock('glossary_toolbar');
			$toolbar->addOrderToAvailableOrders('title', 'Title')
				->setOrder('title')
				->setDefaultOrder('title')
				->setCollection($glossary);
			$pager = $this->getLayout()->getBlock('product_list_toolbar_pager');
			$limits = $toolbar->getAvailableLimit();
			$pagerLimit = array();
			foreach ($limits as $_limit=>$value) {
				$pagerLimit[] = (int)$value;
			}
			$pager->setAvailableLimit($pagerLimit);
			$pager->setCollection($glossary);
		}
		Mage::register('glossary', $glossary);
		$this->renderLayout();
	}

	/**
	 *
	 */
	public function viewAction () {
		$glossary_id = $this->getRequest()->getParam('id');
		if ($glossary_id != null) {
			$glossary = Mage::getModel('glossary/glossary');
			$glossary->load($glossary_id);
			Mage::register('glossary', $glossary);
		}
		$this->loadLayout();
		$this->renderLayout();
	}

}