<?php
class MG_Megaforum_Block_Adminhtml_Topic_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("megaforum_form", array("legend"=>Mage::helper("megaforum")->__("Item information")));

				
						$fieldset->addField("topic_id", "text", array(
						"label" => Mage::helper("megaforum")->__("Topic Id"),
						"name" => "topic_id",
						));
					
						$fieldset->addField("forum_id", "text", array(
						"label" => Mage::helper("megaforum")->__("Forum Id"),
						"name" => "forum_id",
						));
					
						$fieldset->addField("topic_name", "text", array(
						"label" => Mage::helper("megaforum")->__("Topic Name"),
						"name" => "topic_name",
						));
					
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('created_at', 'date', array(
						'label'        => Mage::helper('megaforum')->__('Created At'),
						'name'         => 'created_at',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$fieldset->addField("created_by", "text", array(
						"label" => Mage::helper("megaforum")->__("Created By"),
						"name" => "created_by",
						));
					
						$fieldset->addField("views", "text", array(
						"label" => Mage::helper("megaforum")->__("Views"),
						"name" => "views",
						));
						
						$fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('megaforum')->__('Status'),
						'values'   => MG_Megaforum_Block_Adminhtml_Topic_Grid::getValueArray1(),
						'name' => 'status',
						));
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);
					

				if (Mage::getSingleton("adminhtml/session")->getTopicData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getTopicData());
					Mage::getSingleton("adminhtml/session")->setTopicData(null);
				} 
				elseif(Mage::registry("topic_data")) {
				    $form->setValues(Mage::registry("topic_data")->getData());
				}
				return parent::_prepareForm();
		}
}
