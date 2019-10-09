<?php


class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Registrocorrispettivi_Totali extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_registrocorrispettivi_totali";
	$this->_blockGroup = "registrocorrispettivi";
	$this->_headerText = Mage::helper("registrocorrispettivi")->__("Registro Corrispettivi");
	parent::__construct();
        $this->_removeButton('add');


    }

}