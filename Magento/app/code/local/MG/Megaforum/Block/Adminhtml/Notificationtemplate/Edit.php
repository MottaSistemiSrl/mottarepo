<?php
	
class MG_Megaforum_Block_Adminhtml_Notificationtemplate_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "notificationtemplate_id";
				$this->_blockGroup = "megaforum";
				$this->_controller = "adminhtml_notificationtemplate";
				$this->_updateButton("save", "label", Mage::helper("megaforum")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("megaforum")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("megaforum")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("notificationtemplate_data") && Mage::registry("notificationtemplate_data")->getId() ){

				    return Mage::helper("megaforum")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("notificationtemplate_data")->getId()));

				} 
				else{

				     return Mage::helper("megaforum")->__("Add Item");

				}
		}
}