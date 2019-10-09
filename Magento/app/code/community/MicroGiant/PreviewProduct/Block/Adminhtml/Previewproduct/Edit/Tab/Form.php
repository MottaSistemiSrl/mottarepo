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
 * Previewproduct Edit Form Content Tab Block
 * 
 * @category    Magestore
 * @package     Magestore_PreviewProduct
 * @author      Magestore Developer
 */
class MicroGiant_PreviewProduct_Block_Adminhtml_Previewproduct_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare tab form's information
     *
     * @return MicroGiant_PreviewProduct_Block_Adminhtml_Previewproduct_Edit_Tab_Form
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        if (Mage::getSingleton('adminhtml/session')->getPreviewProductData()) {
            $data = Mage::getSingleton('adminhtml/session')->getPreviewProductData();
            Mage::getSingleton('adminhtml/session')->setPreviewProductData(null);
        } elseif (Mage::registry('previewproduct_data')) {
            $data = Mage::registry('previewproduct_data')->getData();
        }
        $fieldset = $form->addFieldset('previewproduct_form', array(
            'legend'=>Mage::helper('previewproduct')->__('Item information')
        ));

        $fieldset->addField('title', 'text', array(
            'label'        => Mage::helper('previewproduct')->__('Title'),
            'class'        => 'required-entry',
            'required'    => true,
            'name'        => 'title',
        ));

        $fieldset->addField('filename', 'file', array(
            'label'        => Mage::helper('previewproduct')->__('File'),
            'required'    => false,
            'name'        => 'filename',
        ));

        $fieldset->addField('status', 'select', array(
            'label'        => Mage::helper('previewproduct')->__('Status'),
            'name'        => 'status',
            'values'    => Mage::getSingleton('previewproduct/status')->getOptionHash(),
        ));

        $fieldset->addField('content', 'editor', array(
            'name'        => 'content',
            'label'        => Mage::helper('previewproduct')->__('Content'),
            'title'        => Mage::helper('previewproduct')->__('Content'),
            'style'        => 'width:700px; height:500px;',
            'wysiwyg'    => false,
            'required'    => true,
        ));

        $form->setValues($data);
        return parent::_prepareForm();
    }
}