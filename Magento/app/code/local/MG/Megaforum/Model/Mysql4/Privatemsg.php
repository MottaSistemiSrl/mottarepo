<?php
class MG_Megaforum_Model_Mysql4_Privatemsg extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("megaforum/privatemsg", "privatemsg_id");
    }
}