<?php
 /**
 * Class     Token.php
 * @category Bitbull_Bancasellapro
 * @package  Bitbull
 * @author   Mirko Cesaro <mirko.cesaro@gmail.com>
 */

class Bitbull_BancaSellaPro_Model_Resource_Token extends Mage_Core_Model_Resource_Db_Abstract {

    protected function _construct()
    {
        $this->_init('bitbull_bancasellapro/token', 'entity_id');
        $this->_idFieldName='entity_id';
    }

} 