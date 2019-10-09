<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 31/07/13
 * Time: 15.09
 * To change this template use File | Settings | File Templates.
 */
class Sevenlike_Registrocorrispettivi_Model_Config_Inserimento extends Mage_Core_Model_Abstract
{
    const SPEDIZIONE = 'spedizione';
    const INVOICE = 'fattura';
    const MANUALE = 'manuale';
    public function toOptionArray()
    {
        return array(
            array('value' => self::INVOICE, 'label'=>'Creazione Fattura'),
            array('value' => self::SPEDIZIONE, 'label'=>'Creazione Spedizione'),
            array('value' => self::MANUALE, 'label'=>'Manuale su Ordine'),
        );
    }

}
