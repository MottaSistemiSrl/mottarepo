<?php

class MG_Megaforum_Block_Adminhtml_Topic_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("topicGrid");
				$this->setDefaultSort("topic_id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("megaforum/topic")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("topic_id", array(
				"header" => Mage::helper("megaforum")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "topic_id",
				));
                
				$this->addColumn("forum_id", array(
				"header" => Mage::helper("megaforum")->__("Forum Id"),
				"index" => "forum_id",
				));
				$this->addColumn("topic_name", array(
				"header" => Mage::helper("megaforum")->__("Topic Name"),
				"index" => "topic_name",
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
				
				$this->addColumn("views", array(
				"header" => Mage::helper("megaforum")->__("Views"),
				"index" => "views",
				));
				
				$this->addColumn('status', array(
				'header' => Mage::helper('megaforum')->__('Status'),
				'index' => 'status',
				'type' => 'options',
				'options'=>MG_Megaforum_Block_Adminhtml_Topic_Grid::getOptionArray1(),				
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
			$this->setMassactionIdField('topic_id');
			$this->getMassactionBlock()->setFormFieldName('topic_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_topic', array(
					 'label'=> Mage::helper('megaforum')->__('Remove Topic'),
					 'url'  => $this->getUrl('*/adminhtml_topic/massRemove'),
					 'confirm' => Mage::helper('megaforum')->__('Are you sure?')
				));
			return $this;
		}
		
		static public function getOptionArray1()
		{
            $data_array=array(); 
			$data_array['Active']='Active';
			$data_array['Inactive']='Inactive';
            return($data_array);
		}
		static public function getValueArray1()
		{
            $data_array=array();
			foreach(MG_Megaforum_Block_Adminhtml_Topic_Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
			

}