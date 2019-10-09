<?php
class MG_Megaforum_Model_Mysql4_Topic extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("megaforum/topic", "topic_id");
    }
}