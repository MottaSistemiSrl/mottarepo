<?php

class MG_Megaforum_Block_Profilecreation extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();

        $id = $this->getRequest()->getParam('id');
        $currentUser = Mage::getModel('customer/customer')->load($id);
        $userCreationCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect(array('name', 'description', 'image', 'sku_cartamodello'))
            ->addAttributeToFilter('sku', array('like' => 'customer-creation-' . $currentUser->getId() . '%'))
            ->addAttributeToFilter('attribute_set_id', '10');
        $this->setUserCreationCollection($userCreationCollection);

    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'profile.creation.pager');
        $pager->setAvailableLimit(array(9 => 9, 18 => 18, 27 => 27));
        $pager->setPageVarName('c');
        $pager->setCollection($this->getUserCreationCollection());
        $this->setChild('pager', $pager);
        $this->getUserCreationCollection()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}