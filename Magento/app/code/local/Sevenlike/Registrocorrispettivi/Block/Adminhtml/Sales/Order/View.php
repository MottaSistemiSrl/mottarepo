<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 26/08/13
 * Time: 12.32
 * To change this template use File | Settings | File Templates.
 */
class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Sales_Order_View extends Mage_Adminhtml_Block_Sales_Order_View
{
    public function __construct()
    {
        parent::__construct();
        $reg = Mage::getModel('registrocorrispettivi/registrocorrispettivi');
        $stati = array('canceled','closed','holded');

        if ($this->_isAllowedAction('contabilizza') &&
              Mage::helper('registrocorrispettivi')->getModelloInserimento(Mage::app()->getStore()->getId())==Sevenlike_Registrocorrispettivi_Model_Config_Inserimento::MANUALE
            && !$reg->isOrdineContabilizzato($this->getOrder()->getIncrementId())&&!in_array($this->getOrder()->getState(),$stati)) {
            $message = Mage::helper('registrocorrispettivi')->__("L\\'ordine verrà aggiunto al registro dei corrispettivi,continuare?");
            $this->_addButton('order_contabilizza', array(
                'label'     => Mage::helper('registrocorrispettivi')->__('Contabilizza'),
                'onclick'   => 'deleteConfirm(\''.$message.'\', \'' . $this->getContabilizzaUrl() . '\')',
            ));
        }
    }

    public function getContabilizzaUrl()
    {
        return $this->getUrl('registrocorrispettivi/adminhtml_registrocorrispettivi/aggiungiOrdine');
    }
}
