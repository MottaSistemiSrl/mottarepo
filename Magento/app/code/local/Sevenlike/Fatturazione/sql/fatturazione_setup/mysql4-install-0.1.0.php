<?php
/* @var $installer Mage_Customer_Model_Entity_Setup */
$installer = $this;
$installer->startSetup();
/* @var $addressHelper Mage_Customer_Helper_Address */
$addressHelper = Mage::helper('customer/address');
$store         = Mage::app()->getStore(Mage_Core_Model_App::ADMIN_STORE_ID);

/* @var $eavConfig Mage_Eav_Model_Config */
$eavConfig = Mage::getSingleton('eav/config');

// update customer address user defined attributes data
$attributes = array(

    'partita_iva'           => array(
        'label'    => 'Partita Iva',
        'type'     => 'varchar',
        'input'    => 'text',
        'is_user_defined'   => 1,
        'is_system'         => 0,
        'is_visible'        => 1,
        'sort_order'        => 141,
        'is_required'       => 0,
        'multiline_count'   => 0,

    ),
    'indirizzo_aziendale'           => array(
        'label'    => 'Indirizzo Aziendale',
        'type'     => 'int',
        'input'    => 'boolean',
        'source' => 'eav/entity_attribute_source_table',
        'is_user_defined'   => 1,
        'is_system'         => 0,
        'is_visible'        => 1,
        'sort_order'        => 142,
        'is_required'       => 0,
        'multiline_count'   => 0,
    ),
    'richiesta_fattura'           => array(
        'label'    => 'Richiesta Fattura',
        'type'     => 'int',
        'input'    => 'boolean',
        'source' => 'eav/entity_attribute_source_table',
        'is_user_defined'   => 1,
        'is_system'         => 0,
        'is_visible'        => 1,
        'sort_order'        => 142,
        'is_required'       => 0,
        'multiline_count'   => 0,
    ),
);


$addVatId = true;
$attribute = $eavConfig->getAttribute('customer_address', 'vat_id');
if( $attribute->getId()>0){
    $addVatId = false;
}else{
    $attributes['vat_id']=array(
        'label'    => 'Codice Fiscale',
        'type'     => 'varchar',
        'input'    => 'text',
        'is_user_defined'   => 1,
        'is_system'         => 0,
        'is_visible'        => 1,
        'sort_order'        => 140,
        'is_required'       => 0,
        'multiline_count'   => 0,

    );
}
foreach ($attributes as $attributeCode => $data) {

    $installer->addAttribute('customer_address',$attributeCode,$data);


    $attribute = $eavConfig->getAttribute('customer_address', $attributeCode);

    $attribute->setWebsite($store->getWebsite());
    $attribute->addData($data);
    $usedInForms = array(
        'adminhtml_customer_address',
        'customer_address_edit',
        'customer_register_address'
    );
    $attribute->setData('used_in_forms', $usedInForms);
    $attribute->save();
}

$installer->run("
ALTER TABLE {$this->getTable('sales_flat_quote_address')} ADD COLUMN `partita_iva` varchar(255) DEFAULT NULL;
ALTER TABLE {$this->getTable('sales_flat_order_address')} ADD COLUMN `partita_iva` varchar(255) DEFAULT NULL;
ALTER TABLE {$this->getTable('sales_flat_quote_address')} ADD COLUMN `indirizzo_aziendale` smallint(6) DEFAULT NULL;
ALTER TABLE {$this->getTable('sales_flat_order_address')} ADD COLUMN `indirizzo_aziendale` smallint(6) DEFAULT NULL;
ALTER TABLE {$this->getTable('sales_flat_quote_address')} ADD COLUMN `richiesta_fattura` SMALLINT(6) DEFAULT NULL;
ALTER TABLE {$this->getTable('sales_flat_order_address')} ADD COLUMN `richiesta_fattura` SMALLINT(6) DEFAULT NULL;
");
if($addVatId){
    $installer->run("
ALTER TABLE {$this->getTable('sales_flat_quote_address')} ADD COLUMN `vat_id` varchar(255) DEFAULT NULL;
ALTER TABLE {$this->getTable('sales_flat_order_address')} ADD COLUMN `vat_id` varchar(255) DEFAULT NULL;
");
}

$installer->endSetup();