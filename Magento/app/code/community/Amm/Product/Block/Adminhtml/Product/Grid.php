<?php
class Amm_Product_Block_Adminhtml_Product_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
       parent::__construct();
       $this->setId('product_grid');
       $this->setDefaultSort('id');
       $this->setDefaultDir('DESC');
       $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
      $collection = Mage::getModel('product/product')->getCollection()->setOrder('id','desc');
	  
	  $resource = Mage::getSingleton('core/resource');
    $table14 = $resource->getTableName('eav_attribute');

	  $collection->getSelect()
	  ->join( array('ce1' => $resource->getTableName('customer_entity')), 'ce1.entity_id=main_table.id_customer', array('email' => 'email'))
	  ->join( array('ce2' => $resource->getTableName('customer_entity_varchar')), 'ce2.entity_id=main_table.id_customer AND ce2.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=ce2.entity_type_id and ea1.attribute_code=\'firstname\')', array('buyer_first_name' => 'value'))
	  ->join( array('ce3' => $resource->getTableName('customer_entity_varchar')), 'ce3.entity_id=main_table.id_customer AND ce3.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=ce3.entity_type_id and ea1.attribute_code=\'lastname\')', array('buyer_last_name' => 'value'))
	  ->join( array('ce4' => $resource->getTableName('catalog_product_entity')), 'ce4.entity_id=main_table.id_product', array('sku' => 'sku', 'created_at' => 'created_at'))
	  ->join( array('ce5' => $resource->getTableName('catalog_product_entity_decimal')), 'ce5.entity_id=main_table.id_product AND ce5.attribute_id=(select ea1.attribute_id from '.$table14.' as ea1 where ea1.entity_type_id=ce5.entity_type_id and ea1.attribute_code=\'price\')', array('value' => 'value'));
	  
      $this->setCollection($collection);
      return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
		$this->addColumn('ID',
               array(
                    'header' => 'ID',
                    'align' =>'left',
					          'type' => 'number',
                    'filter' => false,
                    'index' => 'id_product',
              ));
		$this->addColumn('First Name',
               array(
                    'header' => 'First Name',
                    'align' =>'left',
					          'type' => 'text',
                    'filter' => false,
                    'index' => 'buyer_first_name',
              ));
		$this->addColumn('Last Name',
               array(
                    'header' => 'Last Name',
                    'align' =>'left',
                    'type' => 'text',
                    'filter' => false,
                    'index' => 'buyer_last_name',
              ));
       $this->addColumn('Email',
               array(
                    'header' => 'Email',
                    'align' =>'left',
                    'type' => 'text',
                    'index' => 'email',
              ));
		$this->addColumn('SKU',
               array(
                    'header' => 'SKU',
                    'align' =>'left',
                    'type' => 'text',
                    'index' => 'sku',
              ));
		$this->addColumn('Created',
               array(
                    'header' => 'Created',
                    'align' =>'left',
                    'type' => 'date',
                    'filter' => false,
                    'index' => 'created_at',
              ));
		$this->addColumn('Price',
               array(
                    'header' => 'Price',
                    'align' =>'left',
					          'type' => 'number',
                    'filter' => false,
                    'index' => 'value',
              ));
         return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
         return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}