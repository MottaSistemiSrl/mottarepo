<?php

class MG_Megaforum_Block_Adminhtml_Forumusertype_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("forumusertypeGrid");
				$this->setDefaultSort("forumusertype_id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("megaforum/forumusertype")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("forumusertype_id", array(
				"header" => Mage::helper("megaforum")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "forumusertype_id",
				));
                
						$this->addColumn('type', array(
						'header' => Mage::helper('megaforum')->__('Type'),
						'index' => 'type',
						'type' => 'options',
						'options'=>MG_Megaforum_Block_Adminhtml_Forumusertype_Grid::getOptionArray5(),				
						));
						
			$this->addExportType('*/*/exportCsv', Mage::helper('sales')->__('CSV')); 
			$this->addExportType('*/*/exportExcel', Mage::helper('sales')->__('Excel'));

				return parent::_prepareColumns();
		}

		public function getRowUrl($row)
		{
			   return $this->getUrl("*/*/edit", array("id" => $row->getId()));
		}


		
		protected function _prepareMassaction()
		{
			$this->setMassactionIdField('forumusertype_id');
			$this->getMassactionBlock()->setFormFieldName('forumusertype_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_forumusertype', array(
					 'label'=> Mage::helper('megaforum')->__('Remove Forumusertype'),
					 'url'  => $this->getUrl('*/adminhtml_forumusertype/massRemove'),
					 'confirm' => Mage::helper('megaforum')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray5()
		{
            $data_array=array(); 
			$data_array['Moderator']='Moderator';
            return($data_array);
		}
		static public function getValueArray5()
		{
            $data_array=array();
			foreach(MG_Megaforum_Block_Adminhtml_Forumusertype_Grid::getOptionArray5() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}