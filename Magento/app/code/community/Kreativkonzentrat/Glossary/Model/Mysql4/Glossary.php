<?php
/**
 * Class Kreativkonzentrat_Glossary_Model_Mysql4_Glossary
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Model_Mysql4_Glossary extends Mage_Core_Model_Mysql4_Abstract
{

	/**
	 *
	 */
	public function _construct () {
		$this->_init('glossary/glossary', 'glossary_id');
	}

	/**
	 * @param Mage_Core_Model_Abstract $object
	 *
	 * @return mixed
	 */
	protected function _afterSave (Mage_Core_Model_Abstract $object) {

		$condition = $this->_getWriteAdapter()->quoteInto('glossary_id = ?', $object->getId());
		//process glossary entry to store relation
		$this->_getWriteAdapter()->delete($this->getTable('glossary_store'), $condition);
		foreach ((array)$object->getData('stores') as $store) {
			$storeArray                = array();
			$storeArray['glossary_id'] = $object->getId();
			$storeArray['store_id']    = $store;
			$this->_getWriteAdapter()->insert($this->getTable('glossary_store'), $storeArray);
		}

		return parent::_afterSave($object);
	}

	/**
	 * @param Mage_Core_Model_Abstract $object
	 *
	 * @return mixed
	 */
	protected function _afterLoad (Mage_Core_Model_Abstract $object) {
		//process glossary entry to store relation
		$select = $this->_getReadAdapter()->select()->from($this->getTable('glossary_store'))->where('glossary_id = ?', $object->getId());
		if ($data = $this->_getReadAdapter()->fetchAll($select)) {
			$storesArray = array();
			foreach ($data as $row) {
				$storesArray[] = $row['store_id'];
			}
			$object->setData('store_id', $storesArray);
		}

		return parent::_afterLoad($object);
	}

}