<?php
/**
 * Class Kreativkonzentrat_Glossary_Model_Adminhtml_Select
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Model_Adminhtml_Select
{

	/**
	 * @return array
	 */
	public function toOptionArray () {
		return array(
			array('value' => 1, 'label' => Mage::helper('core')->__('Yes')),
			array('value' => 0, 'label' => Mage::helper('core')->__('No')),
		);
	}

}