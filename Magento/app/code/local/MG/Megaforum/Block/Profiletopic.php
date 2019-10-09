<?php

class MG_Megaforum_Block_Profiletopic extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();

        $id = $this->getRequest()->getParam('id');
        $currentUser = Mage::getModel('customer/customer')->load($id);

        $userTopicCollection = Mage::getModel('megaforum/topic')
            ->getCollection()
            ->addFieldToFilter('created_by', $currentUser->getEntityId());
        $this->setUserTopicCollection($userTopicCollection);
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $pager = $this->getLayout()->createBlock('page/html_pager', 'profile.topic.pager');
        $pager->setAvailableLimit(array(9 => 9, 18 => 18, 27 => 27));
        $pager->setPageVarName('t');
        $pager->setCollection($this->getUserTopicCollection());
        $this->setChild('pager', $pager);
        $this->getUserTopicCollection()->load();
        return $this;
    }

    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

}