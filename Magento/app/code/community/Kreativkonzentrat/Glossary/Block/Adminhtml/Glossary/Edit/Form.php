<?php
/**
 * Class Kreativkonzentrat_Glossary_Block_Adminhtml_Glossary_Edit_Form
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Block_Adminhtml_Glossary_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

	/**
	 * @return mixed
	 */
	protected function _prepareForm () {
		$form = new Varien_Data_Form(
			array(
				'id' => 'edit_form',
				'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
				'method' => 'post',
				'enctype' => 'multipart/form-data'
			)
		);
		$form->setUseContainer(true);
		$this->setForm($form);
		return parent::_prepareForm();
	}

}