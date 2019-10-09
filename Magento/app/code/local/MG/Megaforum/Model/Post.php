<?php

class MG_Megaforum_Model_Post extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init("megaforum/post");
    }

    public function getLatestPosts($limit = 0)
    {
        $collection = Mage::getModel("megaforum/post")
            ->getCollection()
            ->setOrder("posted_at", "desc")
        ;

        if ($limit > 0) {
            $collection->setPageSize($limit);
        }

        return $collection;
    }

    /**
     * Get the first post of the topic.
     * Will always be the post of the topic's creator
     */
    public function getFirstPost($topicId)
    {
        $collection = $this
            ->getCollection()
            ->setPageSize(1)
            ->addFieldToFilter('topic_id', $topicId)
            ->setOrder('post_id', 'asc')
            ->getFirstItem()
        ;

        return $collection;
    }
}

