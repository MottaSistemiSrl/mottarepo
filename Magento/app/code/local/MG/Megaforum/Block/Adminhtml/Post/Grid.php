<?php

class MG_Megaforum_Block_Adminhtml_Post_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

		public function __construct()
		{
				parent::__construct();
				$this->setId("postGrid");
				$this->setDefaultSort("post_id");
				$this->setDefaultDir("ASC");
				$this->setSaveParametersInSession(true);
		}

		protected function _prepareCollection()
		{
				$collection = Mage::getModel("megaforum/post")->getCollection();
				$this->setCollection($collection);
				return parent::_prepareCollection();
		}
		protected function _prepareColumns()
		{
				$this->addColumn("post_id", array(
				"header" => Mage::helper("megaforum")->__("ID"),
				"align" =>"right",
				"width" => "50px",
			    "type" => "number",
				"index" => "post_id",
				));
                
				$this->addColumn("topic_id", array(
				"header" => Mage::helper("megaforum")->__("Topic Id"),
				"index" => "topic_id",
				));
			/*	$this->addColumn("parent_id", array(
				"header" => Mage::helper("megaforum")->__("Parent Id"),
				"index" => "parent_id",
				)); */
				$this->addColumn("position", array(
				"header" => Mage::helper("megaforum")->__("Position"),
				"index" => "position",
				));
			/*	$this->addColumn("post_type", array(
				"header" => Mage::helper("megaforum")->__("Post Type"),
				"index" => "post_type",
				)); */
				$this->addColumn("post_text", array(
				"header" => Mage::helper("megaforum")->__("Post Text"),
				"index" => "post_text",
				));
				
				$this->addColumn('status', array(
				'header' => Mage::helper('megaforum')->__('Status'),
				'index' => 'status',
				'type' => 'options',
				'options'=>MG_Megaforum_Block_Adminhtml_Post_Grid::getOptionArray1(),				
				));
				
				/*	$this->addColumn('posted_at', array(
						'header'    => Mage::helper('megaforum')->__('Posted At'),
						'index'     => 'posted_at',
						'type'      => 'datetime',
					));
				$this->addColumn("posted_by", array(
				"header" => Mage::helper("megaforum")->__("Posted By"),
				"index" => "posted_by",
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
			$this->setMassactionIdField('post_id');
			$this->getMassactionBlock()->setFormFieldName('post_ids');
			$this->getMassactionBlock()->setUseSelectAll(true);
			$this->getMassactionBlock()->addItem('remove_post', array(
					 'label'=> Mage::helper('megaforum')->__('Remove Post'),
					 'url'  => $this->getUrl('*/adminhtml_post/massRemove'),
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
			foreach(MG_Megaforum_Block_Adminhtml_Post_Grid::getOptionArray1() as $k=>$v){
               $data_array[]=array('value'=>$k,'label'=>$v);		
			}
            return($data_array);

		}
			

}