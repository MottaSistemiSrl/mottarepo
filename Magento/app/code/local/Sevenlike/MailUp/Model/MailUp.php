<?php

class Sevenlike_MailUp_Model_MailUp extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('mailup/mailup');
    }
}