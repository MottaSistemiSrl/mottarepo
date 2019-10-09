<?php
/**
 * Magestore
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Magestore.com license that is
 * available through the world-wide-web at this URL:
 * http://www.magestore.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category    Magestore
 * @package     Magestore_PreviewProduct
 * @copyright   Copyright (c) 2012 Magestore (http://www.magestore.com/)
 * @license     http://www.magestore.com/license-agreement.html
 */

/**
 * Previewproduct Edit Block
 * 
 * @category     Magestore
 * @package     Magestore_PreviewProduct
 * @author      Magestore Developer
 */
class MicroGiant_PreviewProduct_Block_Adminhtml_Previewproduct_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
        
        $this->_objectId = 'id';
        $this->_blockGroup = 'previewproduct';
        $this->_controller = 'adminhtml_previewproduct';
        
        $this->_updateButton('save', 'label', Mage::helper('previewproduct')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('previewproduct')->__('Delete Item'));
        
        $this->_addButton('saveandcontinue', array(
            'label'        => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'    => 'saveAndContinueEdit()',
            'class'        => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('previewproduct_content') == null)
                    tinyMCE.execCommand('mceAddControl', false, 'previewproduct_content');
                else
                    tinyMCE.execCommand('mceRemoveControl', false, 'previewproduct_content');
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }
    
    /**
     * get text to show in header when edit an item
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('previewproduct_data')
            && Mage::registry('previewproduct_data')->getId()
        ) {
            return Mage::helper('previewproduct')->__("Edit Item '%s'",
                                                $this->htmlEscape(Mage::registry('previewproduct_data')->getTitle())
            );
        }
        return Mage::helper('previewproduct')->__('Add Item');
    }
}