<?php
/**
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */

class Bitbull_BancaSellaPro_Model_System_Config_Source_Language
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value' => 0, 'label'=>'--NON ABILITATO--'),
            array('value' => 1, 'label'=>'Italiano'),
            array('value' => 2, 'label'=>'Inglese'),
            array('value' => 3, 'label'=>'Spagnolo'),
            array('value' => 4, 'label'=>'Francese'),
            array('value' => 5, 'label'=>'Tedesco'),
        );
    }

}