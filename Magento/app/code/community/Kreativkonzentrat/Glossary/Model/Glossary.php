<?php
/**
 * Class Kreativkonzentrat_Glossary_Model_Glossary
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Model_Glossary extends Mage_Core_Model_Abstract
{

	protected $_glossaryCollection;

	/**
	 *
	 */
	public function _construct () {
		parent::_construct();
		$this->_init('glossary/glossary');
	}

	/**
	 * @param $request
	 *
	 * @return bool
	 */
	public function FindGlossaryEntry ($request) {
		$helper = Mage::helper('glossary');
		$collection = Mage::getModel('glossary/glossary')->getCollection()->addFilter('status', 1)->addStoreFilter(Mage :: app()->getStore());
		foreach ($collection as $item) {
			if (strcasecmp(urlencode($helper->convertUmlauts($item['title'])), $request) == 0) {
				return $item->getID();
			}
		}
		return false;
	}

	/**
	 * @return string
	 */
	public function getUrl () {
		return Mage::app()->getStore()->getUrl('glossary/entry') . urlencode(Mage::helper('glossary')->convertUmlauts($this->getTitle()));
	}

	/**
	 * @return array
	 */
	public function getTitles () {
		$collection = Mage::getModel('glossary/glossary')->getCollection()->addStoreFilter(Mage :: app()->getStore());
		$titles     = array();
		foreach ($collection as $item) {
			$titles[] = '/(?!(?:[^<]+>|[^>]+<\/a>))(' . htmlentities(str_replace('/', '', $item['title']), ENT_COMPAT, 'UTF-8') . ')/i';
		}
		return $titles;
	}

	/**
	 * @return mixed
	 */
	public function getFilteredCollection () {
		return Mage::getModel('glossary/glossary')->getCollection()->addFilter('status', 1)->addStoreFilter(Mage :: app()->getStore());
	}

	/**
	 * @param $content
	 *
	 * @return mixed
	 */
	public function toHtml ($content) {
		return Mage::helper('cms')->getBlockTemplateProcessor()->filter($content);
	}

	/**
	 * @return mixed
	 */
	public function getGlossaryContent () {
		return $this->toHtml($this->getData('glossary_content'));
	}

}