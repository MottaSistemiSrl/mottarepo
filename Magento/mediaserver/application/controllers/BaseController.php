<?php

abstract class BaseController extends Zend_Controller_Action {
	protected $db;
	private $logger;
	private $session;
	
	public function init() {
		$this->db = Zend_Db_Table::getDefaultAdapter();
		$this->session = new Zend_Session_Namespace('burda');
	}
	
	/**
	 * 
	 * @return SL_LogsHelper
	 */
	public function getLogger() {
		if ($this->logger === null) {
			$this->logger = new SL_LogsHelper();
		}
		
		return $this->logger;
	}
	
	protected function getJsonResponse() {
		$this->_helper->layout->disableLayout();
		$this->getHelper('ViewRenderer')->setNoRender();
		$this->getResponse()->setHeader('Content-Type', 'application/json');
	}
	
	protected function getNoRender() {
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	}
	
	protected function getSession() {
		return $this->session;
	}
	
	//controlla il token passato via get/post
	protected function checkRequestToken()
	{		
		$auth_token = $this->getRequest()->getParam('auth_token', null);		
		if ($auth_token == null || $auth_token != SL_SECRET) {
			throw new Exception("token non valido ".$auth_token);
		}
		return true;
	}
	
	//controlla il token in sessione
	protected function checkSessionToken()
	{		
		$this->getLogger()->logVarDumpToFile(Zend_Log::DEBUG, $this->session);
		$this->getLogger()->logToFile(Zend_Log::DEBUG, $this->session->auth_token." < ---------------- >");
		if($this->session->auth_token === null || $this->session->auth_token != SL_SECRET)
		{
			throw new Exception("errore autenticazione token ");
		}
		return true;
	}
}