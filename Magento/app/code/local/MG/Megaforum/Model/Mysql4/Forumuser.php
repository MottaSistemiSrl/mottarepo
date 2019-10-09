<?php
class MG_Megaforum_Model_Mysql4_Forumuser extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("megaforum/forumuser", "forumuser_id");
    }
}