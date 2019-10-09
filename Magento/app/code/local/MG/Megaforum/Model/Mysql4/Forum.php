<?php
class MG_Megaforum_Model_Mysql4_Forum extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("megaforum/forum", "forum_id");
    }
}