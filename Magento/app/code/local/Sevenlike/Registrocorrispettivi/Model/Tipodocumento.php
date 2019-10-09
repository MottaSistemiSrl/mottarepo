<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 24/07/13
 * Time: 13.56
 * To change this template use File | Settings | File Templates.
 */
class Sevenlike_Registrocorrispettivi_Model_Tipodocumento extends Mage_Core_Model_Abstract
{
    const INVOICE = 'fattura';
    const CREDIT_MEMO = 'creditmemo';
    const CORRISPETTIVO = 'corrispettivo';
    const CORRISPETTIVO_NEG = 'corrispettivo_neg';

    public static function getAsOption(){
        return array(self::CORRISPETTIVO=>'Corrispettivo',self::CORRISPETTIVO_NEG=>'Corrispettivo Negativo');
    }
    public static function getAsOptionFull(){
        return array(self::CREDIT_MEMO=>'Nota di credito',self::INVOICE=>'Fattura',self::CORRISPETTIVO=>'Corrispettivo');
    }

}
