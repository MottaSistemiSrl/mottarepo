<?php

class Sevenlike_Registrocorrispettivi_Block_Adminhtml_Registrocorrispettivi_Totali_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("totaliGrid");
				$this->setDefaultSort("data_comp");
				$this->setDefaultDir("DESC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("registrocorrispettivi/registrocorrispettivi_totali")->getCollection();
            $collection->getSelect()->order(array('data_comp DESC','tipo_doc ASC'));//->setOrder('tipo_doc','ASC');
                	$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("id", array(
				"header" => Mage::helper("registrocorrispettivi")->__("Id"),
				"width" => "50px",
				"index" => "id",
				));
            $this->addColumn('data_comp', array(
                'header' => Mage::helper('registrocorrispettivi')->__('Data Contabilizzazione'),
                'index' => 'data_comp',
                'type'  => 'date',

            ));
         
            $this->addColumn("descrizione", array(
                "header" => Mage::helper("registrocorrispettivi")->__("Descrizione"),
                "width" => "250px",
                "index" => "descrizione",
            ));
           $this->addColumn("importo", array(
				"header" => Mage::helper("registrocorrispettivi")->__("Totale"),
				"index" => "importo",
                    "type"=>"currency",
                'currency' => 'valuta',
				));
				$this->addColumn("iva", array(
				"header" => Mage::helper("registrocorrispettivi")->__("Tasse"),
				"index" => "iva",
                    "type"=>"currency",
                     'currency' => 'valuta',

				));
				$this->addColumn("importo_netto", array(
				"header" => Mage::helper("registrocorrispettivi")->__("Imponibile"),
				"index" => "importo_netto",
                    "type"=>"currency",
                'currency' => 'valuta',
				));
				$this->addColumn("aliquota", array(
				"header" => Mage::helper("registrocorrispettivi")->__("Aliquota"),
				"index" => "aliquota",
                    "type"=>"number",

				));
				$this->addColumn("valuta", array(
				"header" => Mage::helper("registrocorrispettivi")->__("Valuta"),
				"index" => "valuta",
				));
            $this->addColumn("label_pagamento", array(
                "header" => Mage::helper("registrocorrispettivi")->__("Metodo Pagamento"),
                "index" => "label_pagamento",
            ));
           
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return "";//$this->getUrl("*/*/edit", array("id" => $row->getId()));
		}




			

}