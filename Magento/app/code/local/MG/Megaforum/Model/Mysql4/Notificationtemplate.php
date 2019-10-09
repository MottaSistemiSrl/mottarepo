<?php
class MG_Megaforum_Model_Mysql4_Notificationtemplate extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("megaforum/notificationtemplate", "notificationtemplate_id");
    }
}