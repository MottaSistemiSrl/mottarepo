<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2012 Amasty (http://www.amasty.com)
* @package Amasty_Flags
*/
class Amasty_Flags_Block_Adminhtml_Flag_Edit_Tab_Auto extends Mage_Adminhtml_Block_Widget_Form implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    protected function _prepareForm()
    {
        /* @var $model Amasty_Flags_Model_Flag */
        $model = Mage::registry('amflags_flag');

        $form = new Varien_Data_Form();

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('cms')->__('Automatically Apply On Order Status Change And Selected Shipping Method')));

        $statuses = Mage::getSingleton('sales/order_config')->getStatuses();
        
        $values   = array();
        foreach ($statuses as $code => $name)
        {
            $values[] = array('value' => $code, 'label' => $name);
        }
        
        $fieldset->addField('apply_status', 'multiselect', array(
            'name'      => 'apply_status',
            'label'     => Mage::helper('amflags')->__('Order Status'),
            'title'     => Mage::helper('amflags')->__('Order Status'),
            'values'    => $values,
            'note'      => $this->__('Set flag if order changes to one of selected statuses'),
        ));
        
        // shipping methods
        $methods = Mage::getStoreConfig('carriers');
        $flags = Mage::getModel('amflags/flag')->getCollection()->getData();
        
        // hide shipping methods, selected in other flags
        foreach ($flags as $i => $flag) {
            if ($flag['entity_id'] != $model->getData('entity_id')) {
                $applayMethods = array();
                $applayMethods = explode(',',$flag['apply_shipping']);
                if ($applayMethods) {
                    foreach ($applayMethods as $j => $method) {
                        unset($methods[$method]);
                    }
                }
            }
        }
        
        $values   = array();
        foreach ($methods as $code => $method) {
            if (isset($method['title'])) {
                $values[] = array('value' => $code, 'label' => $method['title']);
            } else {
                $values[] = array('value' => $code, 'label' => $code);
            }
        }
                
        $fieldset->addField('apply_shipping', 'multiselect', array(
            'name'      => 'apply_shipping',
            'label'     => Mage::helper('amflags')->__('Order Shipping Method'),
            'title'     => Mage::helper('amflags')->__('Order Shipping Method'),
            'values'    => $values,
            'note'      => $this->__('Set flag if in the order used one of selected shipping methods'),
        ));
        
        // payment methods
        $methods = Mage::getStoreConfig('payment');
        
        // hide payment methods, selected in other flags
        foreach ($flags as $i => $flag) {
            if ($flag['entity_id'] != $model->getData('entity_id')) {
                $applayMethods = array();
                $applayMethods = explode(',',$flag['apply_payment']);
                if ($applayMethods) {
                    foreach ($applayMethods as $j => $method) {
                        unset($methods[$method]);
                    }
                }
            }
        }
        
        $values   = array();
        foreach ($methods as $code => $method) {
            if (isset($method['title'])) {
                $values[] = array('value' => $code, 'label' => $method['title']);
            } else {
                $values[] = array('value' => $code, 'label' => $code);
            }
        }
        
        $fieldset->addField('apply_payment', 'multiselect', array(
            'name'      => 'apply_payment',
            'label'     => Mage::helper('amflags')->__('Order Payment Method'),
            'title'     => Mage::helper('amflags')->__('Order Payment Method'),
            'values'    => $values,
            'note'      => $this->__('Set flag if in the order used one of selected payment methods'),
        ));
        
        $columns = Mage::getModel('amflags/column')->getCollection();
    	$values   = array();
        foreach ($columns as $column) {
            $values[] = array('value' => $column->getEntityId(), 'label' => $column->getAlias());
        }
        
        $fieldset->addField('apply_column', 'select', array(
            'name'      => 'apply_column',
            'label'     => Mage::helper('amflags')->__('Column name'),
            'title'     => Mage::helper('amflags')->__('Column name'),
            'values'    => $values,
            'note'      => $this->__('Assign to column'),
        ));
        
        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
    
    public function getTabLabel()
    {
        return Mage::helper('amflags')->__('Automatic Apply');
    }
    
    public function getTabTitle()
    {
        return Mage::helper('amflags')->__('Automatic Apply');
    }
    
    public function canShowTab()
    {
        return true;
    }
    
    public function isHidden()
    {
        return false;
    }
}
