<?php


class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Registrocorrispettivi extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_registrocorrispettivi";
	$this->_blockGroup = "registrocorrispettivi";
	$this->_headerText = Mage::helper("registrocorrispettivi")->__("Dettagli Corrispettivi");
	$this->_addButtonLabel = Mage::helper("registrocorrispettivi")->__("Aggiungi Dettaglio");
	parent::__construct();
	
	}

}