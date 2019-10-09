<?php
/**
 * @copyright   Copyright (c) 2009-2014 Amasty (http://www.amasty.com)
 */ 
class Amasty_Rma_Block_Adminhtml_Request_Edit_Tab_Request extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $model = $this->getModel();
                
        $form = new Varien_Data_Form();
        $this->setForm($form);
        
        /* @var $hlp Amasty_Rma_Helper_Data */
        $hlp = Mage::helper('amrma');
    
        $fldInfo = $form->addFieldset('information', array('legend'=> $hlp->__('Information')));
//       
         $fldInfo->addField('request_id', 'label', array(
          'label'     => $hlp->__('ID'),
          'name'      => 'request_id',
        ));
         
        $fldInfo->addField('increment_id', 'link', array(
          'label'     => $hlp->__('Order #'),
          'href' => Mage::helper('adminhtml')->getUrl("adminhtml/sales_order/view", array(
              'order_id' => $model->getOrderId()
            )),
          'name'      => 'increment_id',
        ));
        
        $fldInfo->addField('email', 'label', array(
          'label'     => $hlp->__('Email'),
          'name'      => 'email',
        ));
        
        $form->setValues($model); 
        
        $fldInfo->addField('link', 'link', array(
          'label'     => $hlp->__('Customer'),
          'href' => Mage::helper('adminhtml')->getUrl("adminhtml/customer/edit", array(
              'id' => $model->getId()
          )),
          'value' => $model->getCustomerName()
          
        ));
        
        if ($model->getAllowCreateLabel()){
            $booleanOptions = $hlp->getBooleanOptions();
            
            $fldInfo->addField('code', 'label', array(
                'label' => $hlp->__('Code'),
                'value' => $model->getCode()
            ));
            
            $fldInfo->addField('is_shipped', 'label', array(
                'label' => $hlp->__('Is Shipped'),
                'value' => isset($booleanOptions[$model->getIsShipped()]) ?
                            $booleanOptions[$model->getIsShipped()] :
                            '-'
                
            ));
//            
//            $fldInfo->addField('is_shipped', 'select', array(
//                'label'     => $hlp->__('Is Shipped'),
//                'name'      => 'is_shipped',
//                'required'  => true,
//                'options'   => $booleanOptions,
//                'value'     => $model->getIsShipped()
//            ));
            
            $fldInfo->addField('shipping_label', 'link', array(
                'label'     => $hlp->__(''),
                'href' => Mage::getUrl("amrma/guest/export", array(
                    "code" => $model->getCode()
                )),
                'onclick' => 'window.open(this.href, \''. $hlp->__('Printing') .'\', \'menubar=yes,location=yes,resizable=no,scrollbars=no,status=yes,width=500,height=500\')    ; return false;',
                'value' => $hlp->__("View Shipping Label")
            ));
        }
            
        
        
        return parent::_prepareForm();
    }
    
}
?>