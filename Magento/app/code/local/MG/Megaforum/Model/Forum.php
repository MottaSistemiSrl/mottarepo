<?php

class MG_Megaforum_Model_Forum extends Mage_Core_Model_Abstract
{
    protected function _construct(){

       $this->_init("megaforum/forum");

    }

    public function getForumsOrderedByTopicsCount($limit = 0)
    {

        $collection = Mage::getModel("megaforum/forum")->getCollection();
        $collection->getSelect()->join( array('table_alias' => 'burda.topic'), 'main_table.forum_id = table_alias.forum_id', array('main_table.forum_id', 'count(main_table.forum_id) as total_topics'), 'schema_name_if_different');
        $collection->getSelect()->group(array('main_table.forum_id'));
        $collection->setOrder("total_topics", "desc");

        if($limit > 0)
        {
            $collection->setPageSize($limit);
        }

        return $collection;

    }

}
	 