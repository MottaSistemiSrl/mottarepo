<?php
class Amm_Product_Block_Adminhtml_Grid extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    public function __construct()
    {
     //where is the controller
     $this->_controller = 'adminhtml_product';
     $this->_blockGroup = 'product';
     //text in the admin header
     $this->_headerText = "All Customer's Products";
     //value of the add button
     //$this->_addButtonLabel = 'Add a contact';
     parent::__construct();
     $this->_removeButton('add');
    }
}