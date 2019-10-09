<?php

class MicroGiant_PreviewProduct_Block_Adminhtml_Catalog_Product_Edit extends Mage_Adminhtml_Block_Catalog_Product_Edit {

    public function __construct() {
        parent::__construct();
        $this->setTemplate('previewproduct/product/edit.phtml');
        $this->setId('product_edit');
    }

    protected function _prepareLayout() {
        $_product = $this->getProduct();
        $this->setChild('preview_button', $this->getLayout()->createBlock('adminhtml/widget_button')
                        ->setData(array(
                            'label' => Mage::helper('catalog')->__('Preview'),
                            'onclick' => 'ajaxPreparePreview()',
                        ))
        );

        return parent::_prepareLayout();
    }

    public function getPreviewButtonHtml() {
        return $this->getChildHtml('preview_button');
    }

}
