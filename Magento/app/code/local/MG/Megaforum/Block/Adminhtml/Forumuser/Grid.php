<?php

class MG_Megaforum_Block_Adminhtml_Forumuser_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("forumuserGrid");
				$this->setDefaultSort("forumuser_id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("megaforum/forumuser")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("forumuser_id", array(
				"header" => Mage::helper("megaforum")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "forumuser_id",
				));
				
				$this->addColumn("image", array(
				"header" => Mage::helper("megaforum")->__("Image"),
				"index" => "image",
				'renderer'  => 'MG_Megaforum_Block_Adminhtml_Forumuser_Renderer_Image',
				));
                
				$this->addColumn("user_id", array(
				"header" => Mage::helper("megaforum")->__("User Id"),
				"index" => "user_id",
				));
				
				$this->addColumn("user_type", array(
				"header" => Mage::helper("megaforum")->__("User Type"),
				"index" => "user_type",
				'type' => 'options',
				'options'=>MG_Megaforum_Block_Adminhtml_Forumuser_Grid::getOptionArray1(),
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
			$this->setMassactionIdField('forumuser_id');
			$this->getMassactionBlock()->setFormFieldName('forumuser_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_forumuser', array(
					 'label'=> Mage::helper('megaforum')->__('Remove Forumuser'),
					 'url'  => $this->getUrl('*/adminhtml_forumuser/massRemove'),
					 'confirm' => Mage::helper('megaforum')->__('Are you sure?')
				));
			return $this;
		}
		
		static public function getOptionArray1()
		{
            $data_array=array(); 
			$data_array['Normal']='Normal';
			$data_array['Moderator']='Moderator';
            return($data_array);
		}
		
		static public function getValueArray1()
		{
            $data_array=array();
			foreach(MG_Megaforum_Block_Adminhtml_Forumuser_Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);
		}
			

}