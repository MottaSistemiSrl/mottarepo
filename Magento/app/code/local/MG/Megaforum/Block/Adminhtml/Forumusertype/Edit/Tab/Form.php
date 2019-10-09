<?php
class MG_Megaforum_Block_Adminhtml_Forumusertype_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("megaforum_form", array("legend"=>Mage::helper("megaforum")->__("Item information")));

				
						$fieldset->addField("forumusertype_id", "hidden", array(
						"label" => Mage::helper("megaforum")->__("Forumusertype Id"),
						"name" => "forumusertype_id",
						));
									
						 $fieldset->addField('type', 'select', array(
						'label'     => Mage::helper('megaforum')->__('Type'),
						'values'   => MG_Megaforum_Block_Adminhtml_Forumusertype_Grid::getValueArray5(),
						'name' => 'type',
						));

				if (Mage::getSingleton("adminhtml/session")->getForumusertypeData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getForumusertypeData());
					Mage::getSingleton("adminhtml/session")->setForumusertypeData(null);
				} 
				elseif(Mage::registry("forumusertype_data")) {
				    $form->setValues(Mage::registry("forumusertype_data")->getData());
				}
				return parent::_prepareForm();
		}
}
