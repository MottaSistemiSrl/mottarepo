<?php


class MG_Megaforum_Block_Adminhtml_Post extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_post";
	$this->_blockGroup = "megaforum";
	$this->_headerText = Mage::helper("megaforum")->__("Post Manager");
	$this->_addButtonLabel = Mage::helper("megaforum")->__("Add New Item");
	parent::__construct();
	
	}

}