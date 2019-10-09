<?php
/**
 * Class Kreativkonzentrat_Glossary_Controller_Router
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Controller_Router extends Mage_Core_Controller_Varien_Router_Abstract
{

	/**
	 * @param $observer
	 */
	public function initControllerRouters ($observer) {
		$front = $observer->getEvent()->getFront();
		$front->addRouter('glossary/entry', $this);
	}

	/**
	 * @param Zend_Controller_Request_Http $request
	 *
	 * @return bool
	 */
	public function match (Zend_Controller_Request_Http $request) {
		$kk_params = trim($request->getPathInfo(), '/');
		$kk_params = explode('/', $kk_params);
		if (isset($kk_params[0])) {
			$kkg_param = $kk_params[0];
		}

		if (isset($kk_params[1])) {
			$kkg_param2 = $kk_params[1];
		}
		if (isset($kk_params[2])) {
			$kkg_param3 = $kk_params[2];
		}
		if (isset($kk_params[3])) {
			$kkg_param4 = $kk_params[3];
		}
		if (isset($kkg_param2) && isset($kkg_param3) && isset($kkg_param4) && $kkg_param4 == 'popup') {
			$glossary = Mage::getModel('glossary/glossary');
			$itemId   = $glossary->FindGlossaryEntry($kkg_param3);
			$request->setModuleName('glossary')->setControllerName('view')->setActionName('popup')->setParam('id', $itemId);
			$request->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, 'glossary/view/id?id=' . $itemId);
			return true;
		}
		if (isset($kkg_param) && $kkg_param == 'glossary' && isset($kkg_param2) && $kkg_param2 == 'entry') {
			$glossary = Mage::getModel('glossary/glossary');
			$itemId   = $glossary->FindGlossaryEntry($kkg_param3);
			$request->setModuleName('glossary')->setControllerName('view')->setActionName('id')->setParam('id', $itemId);
			$request->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, 'glossary/view/id?id=' . $itemId);
			return true;
		} else {
			return false;
		}
	}
}