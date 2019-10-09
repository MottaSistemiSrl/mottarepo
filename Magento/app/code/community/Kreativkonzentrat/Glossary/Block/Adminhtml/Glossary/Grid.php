<?php
/**
 * Class Kreativkonzentrat_Glossary_Block_Adminhtml_Glossary_Grid
 *
 * @package     Kreativkonzentrat_Glossary
 * @author      Felix Moche <felix@kreativkonzentratd.e>
 * @copyright   2012-2013 Kreativkonzentrat GbR
 */
class Kreativkonzentrat_Glossary_Block_Adminhtml_Glossary_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

	/**
	 *
	 */
	public function __construct () {
		parent::__construct();
		$this->setId('glossarGrid');
		$this->setDefaultSort('glossary_id');
		$this->setDefaultDir('ASC');
		$this->setSaveParametersInSession(true);
	}

	/**
	 * @return mixed
	 */
	protected function _prepareCollection () {
		$collection = Mage::getModel('glossary/glossary')->getCollection();
		$this->setCollection($collection);
		return parent::_prepareCollection();
	}

	/**
	 * @return mixed
	 */
	protected function _prepareColumns () {
		$this->addColumn('glossary_id', array(
			'header' => Mage::helper('glossary')->__('ID'),
			'align' => 'right',
			'width' => '50px',
			'index' => 'glossary_id',
		));
		$this->addColumn('title', array(
			'header' => Mage::helper('glossary')->__('Title'),
			'align' => 'left',
			'index' => 'title',
		));
		if (!Mage::app()->isSingleStoreMode()) {
			$this->addColumn('store_id', array(
				'header' => Mage::helper('cms')->__('Store View'),
				'index' => 'store_id',
				'type' => 'store',
				'store_all' => true,
				'store_view' => true,
				'sortable' => false,
				'filter_condition_callback'
				=> array($this, '_filterStoreCondition'),
			));
		}
		$this->addColumn('status', array(
			'header' => Mage::helper('glossary')->__('Status'),
			'align' => 'left',
			'width' => '80px',
			'index' => 'status',
			'type' => 'options',
			'options' => array(
				1 => 'Enabled',
				0 => 'Disabled',
			),
		));
		$this->addColumn('action', array(
			'header' => Mage::helper('adminhtml')->__('Action'),
			'width' => '100',
			'type' => 'action',
			'getter' => 'getId',
			'actions' => array(
				array(
					'caption' => Mage::helper('adminhtml')->__('Edit'),
					'url' => array('base' => '*/*/edit'),
					'field' => 'id'
				)
			),
			'filter' => false,
			'sortable' => false,
			'index' => 'stores',
			'is_system' => true,
		));
		$this->addExportType('*/*/exportCsv', Mage::helper('adminhtml')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('adminhtml')->__('XML'));
		return parent::_prepareColumns();
	}

	/**
	 * @return $this
	 */
	protected function _prepareMassaction () {
		$this->setMassactionIdField('glossary_id');
		$this->getMassactionBlock()->setFormFieldName('glossary');

		$this->getMassactionBlock()->addItem('delete', array(
			'label' => Mage::helper('catalog')->__('Delete'),
			'url' => $this->getUrl('*/*/massDelete'),
			'confirm' => Mage::helper('catalog')->__('Are you sure?')
		));

		$statuses = Mage::getSingleton('glossary/status')->getOptionArray();

		array_unshift($statuses, array('label' => '', 'value' => ''));
		$this->getMassactionBlock()->addItem('status', array(
			'label' => Mage::helper('catalog')->__('Change status'),
			'url' => $this->getUrl('*/*/massStatus', array('_current' => true)),
			'additional' => array(
				'visibility' => array(
					'name' => 'status',
					'type' => 'select',
					'class' => 'required-entry',
					'label' => Mage::helper('catalog')->__('Status'),
					'values' => $statuses
				)
			)
		));
		return $this;
	}

	/**
	 *
	 */
	protected function _afterLoadCollection () {
		$this->getCollection()->walk('afterLoad');
		parent::_afterLoadCollection();
	}

	/**
	 * @param $row
	 *
	 * @return mixed
	 */
	public function getRowUrl ($row) {
		return $this->getUrl('*/*/edit', array('id' => $row->getId()));
	}

	/**
	 * @param $collection
	 * @param $column
	 */
	protected function _filterStoreCondition ($collection, $column) {
		if (!$value = $column->getFilter()->getValue()) {
			return;
		}
		$this->getCollection()->addStoreFilter($value);
	}

}