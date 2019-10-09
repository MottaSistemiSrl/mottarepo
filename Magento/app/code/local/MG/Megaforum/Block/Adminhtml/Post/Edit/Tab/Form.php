<?php
class MG_Megaforum_Block_Adminhtml_Post_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("megaforum_form", array("legend"=>Mage::helper("megaforum")->__("Item information")));

				
						$fieldset->addField("post_id", "text", array(
						"label" => Mage::helper("megaforum")->__("Post Id"),
						"name" => "post_id",
						));
					
						$fieldset->addField("topic_id", "text", array(
						"label" => Mage::helper("megaforum")->__("Topic Id"),
						"name" => "topic_id",
						));
					
					/*	$fieldset->addField("parent_id", "text", array(
						"label" => Mage::helper("megaforum")->__("Parent Id"),
						"name" => "parent_id",
						)); */
					
						$fieldset->addField("position", "text", array(
						"label" => Mage::helper("megaforum")->__("Position"),
						"name" => "position",
						));
					
					/*	$fieldset->addField("post_type", "text", array(
						"label" => Mage::helper("megaforum")->__("Post Type"),
						"name" => "post_type",
						)); */
					
						$fieldset->addField("post_text", "text", array(
						"label" => Mage::helper("megaforum")->__("Post Text"),
						"name" => "post_text",
						));
					
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);

						$fieldset->addField('posted_at', 'date', array(
						'label'        => Mage::helper('megaforum')->__('Posted At'),
						'name'         => 'posted_at',
						'time' => true,
						'image'        => $this->getSkinUrl('images/grid-cal.gif'),
						'format'       => $dateFormatIso
						));
						$fieldset->addField("posted_by", "text", array(
						"label" => Mage::helper("megaforum")->__("Posted By"),
						"name" => "posted_by",
						));
						
						$fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('megaforum')->__('Status'),
						'values'   => MG_Megaforum_Block_Adminhtml_Post_Grid::getValueArray1(),
						'name' => 'status',
						));
						$dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(
							Mage_Core_Model_Locale::FORMAT_TYPE_SHORT
						);
					

				if (Mage::getSingleton("adminhtml/session")->getPostData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getPostData());
					Mage::getSingleton("adminhtml/session")->setPostData(null);
				} 
				elseif(Mage::registry("post_data")) {
				    $form->setValues(Mage::registry("post_data")->getData());
				}
				return parent::_prepareForm();
		}
}
