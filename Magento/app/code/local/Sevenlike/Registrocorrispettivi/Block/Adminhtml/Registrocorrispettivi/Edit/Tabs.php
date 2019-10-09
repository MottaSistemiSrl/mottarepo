<?php
class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Registrocorrispettivi_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("registrocorrispettivi_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("registrocorrispettivi")->__("Informazioni Dettaglio"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("registrocorrispettivi")->__("Informazioni Dettaglio"),
				"title" => Mage::helper("registrocorrispettivi")->__("Informazioni Dettaglio"),
				"content" => $this->getLayout()->createBlock("registrocorrispettivi/adminhtml_registrocorrispettivi_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
