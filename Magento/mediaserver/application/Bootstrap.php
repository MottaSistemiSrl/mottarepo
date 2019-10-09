<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	/**
	 * Bootstrap autoloader for application resources
	 *
	 * @return Zend_Application_Module_Autoloader
	 */
	protected function _initAutoload() {
		$autoloader = new Zend_Application_Module_Autoloader(array(
				'namespace' => 'Default',
				'basePath'  => dirname(__FILE__),
		));
		//$autoloader->registerNamespace('SL_');
		Zend_Loader_Autoloader::getInstance()->registerNamespace('SL_');
		return $autoloader;
	}

}

