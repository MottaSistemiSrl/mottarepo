<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 06/09/13
 * Time: 17.17
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Model_Config_Indipendenti {

    const WEBSITE = 0;
    const STORE_VIEW = 1;
    public function toOptionArray()
    {
        return array(
            array('value' => self::WEBSITE, 'label'=>'Website'),
            array('value' => self::STORE_VIEW, 'label'=>'Store View'),

        );
    }
}