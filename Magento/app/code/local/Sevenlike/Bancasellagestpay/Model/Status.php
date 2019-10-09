<?php

class Sevenlike_Bancasellagestpay_Model_Status
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => '1', 'label'=>'In attesa (da fatturare)'),
            array('value' => '2', 'label'=>'In elaborazione (fattura automatica)'),
            array('value' => '3', 'label'=>'Completo (fattura automatica)')
        );
    }

}
