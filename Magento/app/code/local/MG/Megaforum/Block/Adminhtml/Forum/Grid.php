<?php

class MG_Megaforum_Block_Adminhtml_Forum_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("forumGrid");
				$this->setDefaultSort("forum_id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("megaforum/forum")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("forum_id", array(
				"header" => Mage::helper("megaforum")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "forum_id",
				));
                
				$this->addColumn("forum_name", array(
				"header" => Mage::helper("megaforum")->__("Forum Name"),
				"index" => "forum_name",
				));
						$this->addColumn('priority', array(
						'header' => Mage::helper('megaforum')->__('Priority'),
						'index' => 'priority',
						'type' => 'options',
						'options'=>MG_Megaforum_Block_Adminhtml_Forum_Grid::getOptionArray8(),				
						));
						
			/*	$this->addColumn("url_key", array(
				"header" => Mage::helper("megaforum")->__("URL Key"),
				"index" => "url_key",
				)); */
						$this->addColumn('status', array(
						'header' => Mage::helper('megaforum')->__('Status'),
						'index' => 'status',
						'type' => 'options',
						'options'=>MG_Megaforum_Block_Adminhtml_Forum_Grid::getOptionArray10(),				
						));
						
				/*	$this->addColumn('created_at', array(
						'header'    => Mage::helper('megaforum')->__('Created At'),
						'index'     => 'created_at',
						'type'      => 'datetime',
					));
				$this->addColumn("created_by", array(
				"header" => Mage::helper("megaforum")->__("Created By"),
				"index" => "created_by",
				));*/
				
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
			$this->setMassactionIdField('forum_id');
			$this->getMassactionBlock()->setFormFieldName('forum_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_forum', array(
					 'label'=> Mage::helper('megaforum')->__('Remove Forum'),
					 'url'  => $this->getUrl('*/adminhtml_forum/massRemove'),
					 'confirm' => Mage::helper('megaforum')->__('Are you sure?')
				));
			return $this;
		}
			
		static public function getOptionArray8()
		{
            $data_array=array(); 
			$data_array['Low']='Low';
			$data_array['Medium']='Medium';
			$data_array['High']='High';
            return($data_array);
		}
		static public function getValueArray8()
		{
            $data_array=array();
			foreach(MG_Megaforum_Block_Adminhtml_Forum_Grid::getOptionArray8() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		
		static public function getOptionArray10()
		{
            $data_array=array(); 
			$data_array['Active']='Active';
			$data_array['Inactive']='Inactive';
            return($data_array);
		}
		static public function getValueArray10()
		{
            $data_array=array();
			foreach(MG_Megaforum_Block_Adminhtml_Forum_Grid::getOptionArray10() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
		

}