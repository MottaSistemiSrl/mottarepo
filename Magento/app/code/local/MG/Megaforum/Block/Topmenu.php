<?php

class MG_Megaforum_Block_Topmenu extends Mage_Core_Block_Template
{

    public function getAllForums()
    {
        $forums = Mage::getModel("megaforum/forum")->getCollection();

        return $forums;
    }

    public function getForumsOrderedByTopicsCount($limit = 0)
    {
        $collection = Mage::getModel("megaforum/forum")->getForumsOrderedByTopicsCount($limit);

        return $collection;
    }

    public function getTopicsWithMostPostsByForum($forumId, $limit = 0)
    {
        $collection = Mage::getModel("megaforum/topic")->getTopicsWithMostPostsByForum($forumId, $limit);

        return $collection;
    }

    public function getLatestPosts($limit = 0)
    {
        $collection = Mage::getModel("megaforum/post")->getLatestPosts($limit);

        return $collection;
    }

}