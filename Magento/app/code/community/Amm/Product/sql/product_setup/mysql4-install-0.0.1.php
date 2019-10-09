<?php

$installer = $this;

$installer->startSetup();

$table = $installer->getConnection()->newTable($installer->getTable('amm_product'))
    ->addColumn('id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => false,
        'primary' => true,
        'identity' => true,
        ), 'ID')
    ->addColumn('id_customer', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => true,
        ), 'ID Customer')
    ->addColumn('id_product', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned' => true,
        'nullable' => true,
        ), 'ID Product')
    ->setComment('amm_product table');
$installer->getConnection()->createTable($table);

$installer->endSetup();