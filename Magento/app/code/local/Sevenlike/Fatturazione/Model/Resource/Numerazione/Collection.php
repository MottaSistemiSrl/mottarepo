<?php
class Sevenlike_Fatturazione_Model_Resource_Numerazione_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    protected function _construct()
    {
            $this->_init('fatturazione/numerazione');
    }
}
