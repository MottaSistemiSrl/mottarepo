<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 27/08/13
 * Time: 14.31
 * To change this template use File | Settings | File Templates.
 */
class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Sales_Order_Grid extends Mage_Adminhtml_Block_Sales_Order_Grid
{
    protected function _prepareMassaction()
    {
        parent::_prepareMassaction();
        if( Mage::helper('registrocorrispettivi')->getModelloInserimento(Mage::app()->getStore()->getId())==Sevenlike_Registrocorrispettivi_Model_Config_Inserimento::MANUALE){
            $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

            $this->getMassactionBlock()->addItem('contabilizza', array(
                'label'=> Mage::helper('registrocorrispettivi')->__('Contabilizza'),
                'url'  => $this->getUrl('registrocorrispettivi/adminhtml_registrocorrispettivi/massContabilizza'),
                'confirm' => Mage::helper('registrocorrispettivi')->__('Sei Sicuro?'),
                'additional' => array(
                    'visibility' => array(
                        'name' => 'data_comp',
                        'type' => 'date',
                        'class' => 'required-entry',
                        'label' => Mage::helper( 'registrocorrispettivi' )->__( 'Data' ),
                        'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
                        'format'       => $dateFormatIso
                    )
                )

            ));
        }
    }
}
