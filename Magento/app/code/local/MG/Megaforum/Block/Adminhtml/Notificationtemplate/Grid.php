<?php

class MG_Megaforum_Block_Adminhtml_Notificationtemplate_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("notificationtemplateGrid");
				$this->setDefaultSort("notificationtemplate_id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("megaforum/notificationtemplate")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("notificationtemplate_id", array(
				"header" => Mage::helper("megaforum")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "notificationtemplate_id",
				));
                
						$this->addColumn('type_id', array(
						'header' => Mage::helper('megaforum')->__('Type Id'),
						'index' => 'type_id',
						//'type' => 'options',
						//'options'=>MF_Megaforum_Block_Adminhtml_Notificationtemplate_Grid::getOptionArray1(),				
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
			$this->setMassactionIdField('notificationtemplate_id');
			$this->getMassactionBlock()->setFormFieldName('notificationtemplate_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_notificationtemplate', array(
					 'label'=> Mage::helper('megaforum')->__('Remove Notificationtemplate'),
					 'url'  => $this->getUrl('*/adminhtml_notificationtemplate/massRemove'),
					 'confirm' => Mage::helper('megaforum')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray1()
		{
            $data_array=array(); 
			$data_array[1]='customer create topic notification';
			$data_array[2]='admin create topic notification';
			$data_array[3]='customer create post notification';
			$data_array[4]='admin create post notification';
			$data_array[5]='sent private message';
			$data_array[6]='sent private message reply';
			$data_array[7]='customer create post (all participants)';
            return($data_array);
		}
		static public function getValueArray1()
		{
            $data_array=array();
			foreach(MG_Megaforum_Block_Adminhtml_Notificationtemplate_Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}