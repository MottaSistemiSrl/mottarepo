<?php

class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Registrocorrispettivi_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        parent::__construct();
        $this->setId("registrocorrispettiviGrid");
        $this->setDefaultSort("data_comp");
        $this->setDefaultDir("DESC");
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel("registrocorrispettivi/registrocorrispettivi")->getCollection();

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn("reg_id", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Id"),
            "width" => "50px",
            "index" => "reg_id",
        ));
        $this->addColumn('data_comp', array(
            'header' => Mage::helper('registrocorrispettivi')->__('Data Contabilizzazione'),
            'index' => 'data_comp',
            'type'  => 'date',
        ));
        $this->addColumn("increment_id", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Numero Ordine"),
            "align" =>"right",
            "width" => "50px",
            "index" => "increment_id",
        ));
        $this->addColumn("tipo", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Tipo"),
            "align" =>"right",
            "type"=>"options",
            "options" => Mage::getSingleton('registrocorrispettivi/tipodocumento')->getAsOption(),
            "width" => "100px",
            "index" => "tipo",
        ));
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', array(
                'header'    => Mage::helper('registrocorrispettivi')->__('Store'),
                'index'     => 'store_id',
                'type'      => 'store',
                'store_view'=> true,
                'display_deleted' => true,
            ));
        }
        $this->addColumn("imponibile", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Totale"),
            "index" => "imponibile",
            "type"=>"currency",
            'currency' => 'valuta',
        ));
        $this->addColumn("iva", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Tassa"),
            "index" => "iva",
            "type"=>"currency",
            'currency' => 'valuta',
            "width" => "100px",

        ));
        $this->addColumn("aliquota", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Aliquota"),
            "index" => "aliquota",
            "type" => "number"

        ));
        $this->addColumn("valuta", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Valuta"),
            "index" => "valuta",
            "width" => "50px",
        ));
        $this->addColumn("imponibile_excl_tax", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Imponibile"),
            "type"=>"currency",
            'currency' => 'valuta',
            "index" => "imponibile_excl_tax",
            "width" => "50px",
        ));
        $this->addColumn("discount_amount", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Sconto"),
            "type"=>"currency",
            'currency' => 'valuta',
            "index" => "discount_amount",
            "width" => "50px",
        ));
        $this->addColumn("note", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Note"),
            "width" => "250px",
            "index" => "note",
        ));
        $this->addColumn("regione", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Regione"),
            "width" => "250px",
            "index" => "regione",
        ));
        $this->addColumn("provincia", array(
            "header" => Mage::helper("registrocorrispettivi")->__("Provincia"),
            "width" => "250px",
            "index" => "provincia",
        ));
        $this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return "";//$this->getUrl("*/*/edit", array("id" => $row->getId()));
    }



    protected function _prepareMassaction()
    {
        $dateFormatIso = Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $this->setMassactionIdField('reg_id');
        $this->getMassactionBlock()->setFormFieldName('reg_ids');
        $this->getMassactionBlock()->setUseSelectAll(true);
        $this->getMassactionBlock()->addItem('remove_registrocorrispettivi', array(
            'label'=> Mage::helper('registrocorrispettivi')->__('Cancella Dettagli'),
            'url'  => $this->getUrl('*/adminhtml_registrocorrispettivi/massRemove'),
            'confirm' => Mage::helper('registrocorrispettivi')->__('Sei Sicuro?')
        ));
        $this->getMassactionBlock()->addItem('move_registrocorrispettivi', array(
            'label'=> Mage::helper('registrocorrispettivi')->__('Sposta Dettagli'),
            'url'  => $this->getUrl('*/adminhtml_registrocorrispettivi/massMove'),
            'confirm' => Mage::helper('registrocorrispettivi')->__('Sei Sicuro?'),
            'additional' => array(
                'visibility' => array(
                    'name' => 'nuova_data',
                    'type' => 'date',
                    'class' => 'required-entry',
                    'required'=>true,
                    'label' => Mage::helper( 'registrocorrispettivi' )->__( 'Nuova Data' ),
                    'input_format' => Varien_Date::DATE_INTERNAL_FORMAT,
                    'format'       => $dateFormatIso
                )
            )

        ));
        $this->getMassactionBlock()->addItem('note_registrocorrispettivi', array(
            'label'=> Mage::helper('registrocorrispettivi')->__('Modifica Note'),
            'url'  => $this->getUrl('*/adminhtml_registrocorrispettivi/massAddNote'),
            'additional' => array(
                'visibility' => array(
                    'name' => 'note',
                    'type' => 'textarea',
                    'class' => 'required-entry',
                    'required'=>true,
                    'label' => Mage::helper( 'registrocorrispettivi' )->__( 'Note' ),

                )
            )

        ));

        return $this;
    }


}