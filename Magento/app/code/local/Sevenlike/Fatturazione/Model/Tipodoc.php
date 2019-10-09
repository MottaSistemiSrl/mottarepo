<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 03/09/13
 * Time: 11.26
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Model_Tipodoc extends Mage_Core_Model_Abstract{
    const FATTURA = 'fattura';
    const NDC = 'ndc';
    const CREDITMEMO = 'creditmemo';
    const CREDITMEMO_NDC = 'creditmemo_ndc';

    public static function getAsOptionInvoice(){
        return array(self::FATTURA=>'Fattura',self::NDC=>'Nota di Consegna');
    }


    public static function getAsOptionCreditmemo(){
        return array(self::CREDITMEMO=>'Nota di Credito (su Fattura)',self::CREDITMEMO_NDC=>'Nota di Credito (su Nota di Consegna)');
    }
}