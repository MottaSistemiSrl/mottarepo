<?php

class MG_Megaforum_Block_Watchlistsingle extends Mage_Core_Block_Template
{

    public function __construct()
    {
        parent::__construct();

        $watchlistCollection = Mage::getModel('megaforum/watchlistsingle')->getCollection();
        $this->setCollection($watchlistCollection);

    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();

        $toolbar = $this->getToolbarBlock();

        // called prepare sortable parameters
        $collection = $this->getCollection();

        // use sortable parameters
        if ($orders = $this->getAvailableOrders()) {
            $toolbar->setAvailableOrders($orders);
        }
        if ($sort = $this->getSortBy()) {
            $toolbar->setDefaultOrder($sort);
        }
        if ($dir = $this->getDefaultDirection()) {
            $toolbar->setDefaultDirection($dir);
        }
        $toolbar->setCollection($collection);

        $this->setChild('toolbar', $toolbar);
        $this->getCollection()->load();
        return $this;
    }

    public function getDefaultDirection()
    {
        return 'asc';
    }

    public function getAvailableOrders()
    {
        //return array('created_at' => 'Created Date', 'watchlist_id' => 'Watchlist ID');
        return array('created_at' => 'Created Date');
    }

    public function getSortBy()
    {
        return 'created_at';
    }

    public function getToolbarBlock()
    {
        $block = $this->getLayout()->createBlock('megaforum/toolbar', microtime());
        return $block;
    }

    public function getMode()
    {
        return $this->getChild('toolbar')->getCurrentMode();
    }

    public function getToolbarHtml()
    {
        return $this->getChildHtml('toolbar');
    }


}