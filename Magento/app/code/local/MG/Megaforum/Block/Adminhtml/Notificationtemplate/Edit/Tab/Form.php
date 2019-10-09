<?php
class MG_Megaforum_Block_Adminhtml_Notificationtemplate_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("megaforum_form", array("legend"=>Mage::helper("megaforum")->__("Item information")));

				
						/*$fieldset->addField("notificationtemplate_id", "text", array(
						"label" => Mage::helper("megaforum")->__("Notificationtemplate Id"),
						"name" => "notificationtemplate_id",
						));*/
									
						 $fieldset->addField('type_id', 'select', array(
						'label'     => Mage::helper('megaforum')->__('Type Id'),
						'values'   => MG_Megaforum_Block_Adminhtml_Notificationtemplate_Grid::getValueArray1(),
						'name' => 'type_id',
						));
						$fieldset->addField("template", "textarea", array(
						"label" => Mage::helper("megaforum")->__("Template"),
						"name" => "template",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getNotificationtemplateData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getNotificationtemplateData());
					Mage::getSingleton("adminhtml/session")->setNotificationtemplateData(null);
				} 
				elseif(Mage::registry("notificationtemplate_data")) {
				    $form->setValues(Mage::registry("notificationtemplate_data")->getData());
				}
				return parent::_prepareForm();
		}
}
