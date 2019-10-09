<?php


class MG_Megaforum_Block_Adminhtml_Forum extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_forum";
	$this->_blockGroup = "megaforum";
	$this->_headerText = Mage::helper("megaforum")->__("Forum Manager");
	$this->_addButtonLabel = Mage::helper("megaforum")->__("Add New Item");
	parent::__construct();
	
	}

}