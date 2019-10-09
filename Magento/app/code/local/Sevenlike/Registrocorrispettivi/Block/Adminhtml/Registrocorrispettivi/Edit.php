<?php
	
class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Registrocorrispettivi_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "reg_id";
				$this->_blockGroup = "registrocorrispettivi";
				$this->_controller = "adminhtml_registrocorrispettivi";
				$this->_updateButton("save", "label", Mage::helper("registrocorrispettivi")->__("Salva Dettaglio"));
				$this->_updateButton("delete", "label", Mage::helper("registrocorrispettivi")->__("Cancella Dettaglio"));






		}

		public function getHeaderText()
		{
				if( Mage::registry("registrocorrispettivi_data") && Mage::registry("registrocorrispettivi_data")->getId() ){

				    return Mage::helper("registrocorrispettivi")->__("Modifica Dettaglio '%s'", $this->htmlEscape(Mage::registry("registrocorrispettivi_data")->getId()));

				} 
				else{

				     return Mage::helper("registrocorrispettivi")->__("Aggiungi dettaglio");

				}
		}
}