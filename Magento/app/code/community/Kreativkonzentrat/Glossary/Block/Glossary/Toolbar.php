<?php
/**
 * Class Kreativkonzentrat_Glossary_Block_Glossary_Toolbar
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Block_Glossary_Toolbar extends Mage_Catalog_Block_Product_List_Toolbar
{
	/**
	 * Set collection to pager
	 *
	 * @param Varien_Data_Collection $collection
	 *
	 * @return Mage_Catalog_Block_Product_List_Toolbar
	 */
	public function setCollection ($collection) {
		$this->_collection = $collection;
		$this->_collection->setCurPage($this->getCurrentPage());
		// we need to set pagination only if passed value integer and more that 0
		$limit = (int)$this->getLimit();
		if ($limit) {
			$this->_collection->setPageSize($limit);
		}
		if ($this->getCurrentOrder()) {
			$this->_collection->setOrder('title', $this->getCurrentDirection());
		}
		return $this;
	}
}