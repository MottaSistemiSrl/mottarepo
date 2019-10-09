<?php

class MG_Megaforum_Model_Topic extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("megaforum/topic");

    }

    public function getTopicsWithMostPostsByForum($forumId, $limit = 0)
    {

        $collection = Mage::getModel("megaforum/topic")->getCollection();
        $collection->getSelect()->join( array('table_alias' => 'burda.post'), 'main_table.topic_id = table_alias.topic_id', array('main_table.topic_id', 'count(main_table.topic_id) as total_posts'), 'schema_name_if_different');
        $collection->addFieldToFilter("main_table.forum_id", $forumId);
        $collection->getSelect()->group(array('main_table.topic_id'));
        $collection->setOrder("total_posts", "asc");

        if($limit > 0)
        {
            $collection->setPageSize($limit);
        }

        return $collection;

    }

    /**
     * Retrieve all user partecipating a specific topic
     * 
     * @param $topicId integer The topic ID
     * @param $userIds array Users id to exclude
     * 
     * @return array
     */
    public function getAllParticipantsRaw($topicId, $userIds)
    {
        $read = Mage::getSingleton('core/resource')->getConnection('core_read');

        // we're using raw sql cuz Zend_Db_Select wont support join table alias
        // http://framework.zend.com/issues/browse/ZF-204
        $result = $read->query('
            SELECT
                `main_table`.`posted_by`,
                `customer_entity`.`email`,
                CONCAT(`1`.`value`, " ", `2`.`value`) as `fullname`
            FROM `post` AS `main_table`
            LEFT JOIN `customer_entity` ON `main_table`.`posted_by` = `customer_entity`.`entity_id`
            JOIN `customer_entity_varchar` as `1` 
                ON `customer_entity`.`entity_id` = `1`.`entity_id` AND `1`.`attribute_id` = 5
            JOIN `customer_entity_varchar` as `2` 
                ON `customer_entity`.`entity_id` = `2`.`entity_id` AND `2`.`attribute_id` = 7
            WHERE
                (`main_table`.`topic_id` = '.$topicId.') AND (`main_table`.`posted_by` NOT IN ('.implode(',', $userIds).'))
            GROUP BY
                `main_table`.`posted_by`
        ');

        return $result->fetchAll();
    }

}

