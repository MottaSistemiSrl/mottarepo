<?php
/**
* @author Amasty Team
* @copyright Copyright (c) 2010-2012 Amasty (http://www.amasty.com)
* @package Amasty_Flags
*/
class Amasty_Flags_Block_Rewrite_Adminhtml_Order_Grid_ModifyJs extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('amflags/modifyjs.phtml');
        return $this;
    }
}