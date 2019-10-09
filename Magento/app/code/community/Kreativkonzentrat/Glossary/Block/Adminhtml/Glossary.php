<?php
/**
 * Class Kreativkonzentrat_Glossary_Block_Adminhtml_Glossary
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Block_Adminhtml_Glossary extends Mage_Adminhtml_Block_Widget_Grid_Container
{

	/**
	 *
	 */
	public function __construct () {
		$this->_controller     = 'adminhtml_glossary';
		$this->_blockGroup     = 'glossary';
		$this->_headerText     = Mage::helper('glossary')->__('Item Manager');
		$this->_addButtonLabel = Mage::helper('glossary')->__('Add Item');
		parent::__construct();
	}
}