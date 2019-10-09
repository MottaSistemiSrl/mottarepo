<?php
class MG_Megaforum_Block_Adminhtml_Forum_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("megaforum_form", array("legend"=>Mage::helper("megaforum")->__("Item information")));

				
						$fieldset->addField("forum_id", "hidden", array(
						"label" => Mage::helper("megaforum")->__("Forum Id"),
						"name" => "forum_id",
						));
					
						$fieldset->addField("forum_name", "text", array(
						"label" => Mage::helper("megaforum")->__("Forum Name"),
						"name" => "forum_name",
						));
						
						if (!Mage::app()->isSingleStoreMode()) {
							$fieldset->addField('stores', 'multiselect', array(
								'label'     => Mage::helper('megaforum')->__('Visible In'),
								'required'  => true,
								'name'      => 'stores[]',
								'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
								'value'     => 'stores'
							));
						} else {
							$fieldset->addField('stores', 'hidden', array(
								'name'      => 'stores[]',
								'value'     => Mage::app()->getStore(true)->getId()
							));
						}
									
						 $fieldset->addField('priority', 'select', array(
						'label'     => Mage::helper('megaforum')->__('Priority'),
						'values'   => MG_Megaforum_Block_Adminhtml_Forum_Grid::getValueArray8(),
						'name' => 'priority',
						));
						
					/*	$fieldset->addField("url_key", "text", array(
						"label" => Mage::helper("megaforum")->__("URL Key"),
						"name" => "url_key",
						)); */
									
						 $fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('megaforum')->__('Status'),
						'values'   => MG_Megaforum_Block_Adminhtml_Forum_Grid::getValueArray10(),
						'name' => 'status',
						));
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('created_at', 'hidden', array(
						'label'        => Mage::helper('megaforum')->__('Created At'),
						'name'         => 'created_at',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$fieldset->addField("created_by", "hidden", array(
						"label" => Mage::helper("megaforum")->__("Created By"),
						"name" => "created_by",
						));
					

				if (Mage::getSingleton("adminhtml/session")->getForumData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getForumData());
					Mage::getSingleton("adminhtml/session")->setForumData(null);
				} 
				elseif(Mage::registry("forum_data")) {
				    $form->setValues(Mage::registry("forum_data")->getData());
				}
				return parent::_prepareForm();
		}
}
