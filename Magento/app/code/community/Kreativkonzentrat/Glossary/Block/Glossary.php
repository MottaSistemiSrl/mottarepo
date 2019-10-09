<?php
/**
 * Class Kreativkonzentrat_Glossary_Block_Glossary
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Block_Glossary extends Mage_Core_Block_Template {

	/**
	 * @return mixed
	 */
	public function getPagerHtml() {
		return $this->getChildHtml('pager');
	}

	/**
	 * @return string
	 */
	public function getCacheKey() {
		return 'glossary' . Mage::app()->getStore()->getId() . $this->getRequest()->getRequestUri();
	}

	/**
	 * @return string | null
	 */
	public function getCrumbTitle() {
		if ($this->getRequest()->getActionName() == 'letter') {
			$letter = array_keys($this->getRequest()->getParams());
			if ($letter != null) {
				return $letter[0];
			}
		}
		$glossary_id = $this->getRequest()->getParam('id');
		if ($glossary_id != null && $glossary_id != '') {
			return Mage::getModel('glossary/glossary')->load($glossary_id)->getTitle();
		}
		return null;
	}

	/**
	 * @return mixed
	 */
	public function _prepareLayout() {
		// show breadcrumbs
		$kk_gloss = $this->getCrumbTitle();
		if (Mage::getStoreConfig('web/default/show_cms_breadcrumbs') && ($breadcrumbs = $this->getLayout()->getBlock('breadcrumbs'))) {
			$breadcrumbs->addCrumb('home', array(
				'label'=>Mage::helper('catalogsearch')->__('Home'),
				'title'=>Mage::helper('catalogsearch')->__('Go to Home Page'),
				'link' => Mage::getBaseUrl()
				)
			);
			//set linked glossary crumb and current entry crumb if this is a detail view
			if ($kk_gloss) {
				$breadcrumbs->addCrumb('glossary', array('label' => Mage::helper('glossary')->__('Glossary'), 'link' => Mage::getUrl('glossary/'),));
				$breadcrumbs->addCrumb('title', array('label' => $kk_gloss, 'title' => $kk_gloss));
			}
			//otherwise just add glossary entry without link
			else {
				$breadcrumbs->addCrumb('glossary', array('label' => Mage::helper('glossary')->__('Glossary'),));
			}
		}
		if ($root = $this->getLayout()->getBlock('root')) {
			$root->addBodyClass('glossary');
		}
		if ($head = $this->getLayout()->getBlock('head')) {
			if ($kk_gloss) {
				$head->setTitle(Mage::helper('glossary')->__('Glossary') . ' - ' . $kk_gloss);
			} else {
				$head->setTitle(Mage::helper('glossary')->__('Glossary'));
			}
			$glossary_id = $this->getRequest()->getParam('id');
			$glossary = Mage::getModel('glossary/glossary')->load($glossary_id);
			$head->setKeywords($glossary->getMetakeywords());
			$head->setDescription($glossary->getMetadescription());
		}
		return parent::_prepareLayout();
	}

	/**
	 * @return mixed
	 */
	public function getGlossary() {
		if (!$this->hasData('glossary')) {
			$this->setData('glossary', Mage::registry('glossary'));
		}
		return $this->getData('glossary');
	}

	/**
	 * @return bool
	 */
	public function getCurrentPage() {
		$layer = Mage::getSingleton('catalog/layer');
		if ($layer) {
			return $layer->getProductCollection()->getCurPage();
		}
		return false;
	}

}