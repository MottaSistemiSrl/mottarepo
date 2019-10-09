<?php
class MG_Megaforum_Block_Adminhtml_Forumuser_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("forumuser_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("megaforum")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("megaforum")->__("Item Information"),
				"title" => Mage::helper("megaforum")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("megaforum/adminhtml_forumuser_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
