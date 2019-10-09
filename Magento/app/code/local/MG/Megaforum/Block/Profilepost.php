<?php

class MG_Megaforum_Block_Profilepost extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();

        $id = $this->getRequest()->getParam('id');
        $currentUser = Mage::getModel('customer/customer')->load($id);
        $userPostCollection = Mage::getModel('megaforum/post')
            ->getCollection()
            ->addFieldToFilter('posted_by', $currentUser->getEntityId());
        $this->setUserPostCollection($userPostCollection);

    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'profile.post.pager');
        $pager->setAvailableLimit(array(9 => 9, 18 => 18, 27 => 27));
        $pager->setPageVarName('p');
        $pager->setCollection($this->getUserPostCollection());
        $this->setChild('pager', $pager);
        $this->getUserPostCollection()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}