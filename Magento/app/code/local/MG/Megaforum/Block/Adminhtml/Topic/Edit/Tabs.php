<?php
class MG_Megaforum_Block_Adminhtml_Topic_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
		public function __construct()
		{
				parent::__construct();
				$this->setId("topic_tabs");
				$this->setDestElementId("edit_form");
				$this->setTitle(Mage::helper("megaforum")->__("Item Information"));
		}
		protected function _beforeToHtml()
		{
				$this->addTab("form_section", array(
				"label" => Mage::helper("megaforum")->__("Item Information"),
				"title" => Mage::helper("megaforum")->__("Item Information"),
				"content" => $this->getLayout()->createBlock("megaforum/adminhtml_topic_edit_tab_form")->toHtml(),
				));
				return parent::_beforeToHtml();
		}

}
