<?php
/**
 * Class Kreativkonzentrat_Glossary_Block_Adminhtml_Glossary_Edit
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Block_Adminhtml_Glossary_Edit extends Mage_Adminhtml_Block_Widget_Form_Container {

	/**
	 *
	 */
	public function __construct () {
		parent::__construct();
		$this->_objectId    = 'id';
		$this->_blockGroup  = 'glossary';
		$this->_controller  = 'adminhtml_glossary';
		$this->_updateButton('save', 'label', Mage::helper('catalog')->__('Save'));
		$this->_updateButton('delete', 'label', Mage::helper('catalog')->__('Delete'));
		$this->_addButton('saveandcontinue', array(
			'label'     => Mage::helper('catalog')->__('Save and Continue Edit'),
			'onclick'   => 'saveAndContinueEdit()',
			'class'     => 'save',
		), -100);
		$this->_formScripts[] = "
			function toggleEditor() {
				if (tinyMCE.getInstanceById('glossary_content') == null) {
					tinyMCE.execCommand('mceAddControl', false, 'glossary_content');
				} else {
					tinyMCE.execCommand('mceRemoveControl', false, 'glossary_content');
				}
			}
			function saveAndContinueEdit(){
				editForm.submit($('edit_form').action+'back/edit/');
			}
		";
	}

	/**
	 * @return mixed
	 */
	protected function _prepareLayout () {
		$return = parent::_prepareLayout();
		if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
			$this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
		}
		return $return;
	}

	/**
	 * @return mixed
	 */
	public function getHeaderText () {
		if (Mage::registry('glossary_data') && Mage::registry('glossary_data')->getId()) {
			return Mage::helper('glossary')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('glossary_data')->getTitle()));
		}
		return Mage::helper('glossary')->__('Add Item');
	}

}