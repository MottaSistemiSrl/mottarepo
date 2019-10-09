<?php
class MG_Megaforum_Block_Adminhtml_Forumuser_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("megaforum_form", array("legend"=>Mage::helper("megaforum")->__("Item information")));

				
						$fieldset->addField("forumuser_id", "hidden", array(
						"label" => Mage::helper("megaforum")->__("Forumuser Id"),
						"name" => "forumuser_id",
						));
					
						$fieldset->addField("user_id", "text", array(
						"label" => Mage::helper("megaforum")->__("User Id"),
						"name" => "user_id",
						));
					
						$fieldset->addField("user_type", "select", array(
						"label" => Mage::helper("megaforum")->__("User Type"),
						'values'   => MG_Megaforum_Block_Adminhtml_Forumuser_Grid::getValueArray1(),
						"name" => "user_type",
						)); 
									
						$fieldset->addField('image', 'image', array(
						'label' => Mage::helper('megaforum')->__('Image'),
						'name' => 'image',
						'note' => '(*.jpg, *.png, *.gif)',
						));

				if (Mage::getSingleton("adminhtml/session")->getForumuserData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getForumuserData());
					Mage::getSingleton("adminhtml/session")->setForumuserData(null);
				} 
				elseif(Mage::registry("forumuser_data")) {
				    $form->setValues(Mage::registry("forumuser_data")->getData());
				}
				return parent::_prepareForm();
		}
}
