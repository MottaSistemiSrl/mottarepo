<?php

/**
 * @var Mage_Catalog_Model_Resource_Setup $installer
 */
$installer = $this;
$installer->startSetup();

$entity_type_id = $installer->getEntityTypeId(Mage_Catalog_Model_Product::ENTITY);
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'sl_video_id',
    array(
        'attribute_set' => 'Video',
        'group' => '',
        'type' => 'int',
        'backend' => '',
        'input_renderer' => 'mediaserver/catalog_product_helper_form_video',
        'label' => 'Video',
        'class' => '',
        'global' => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible' => true,
        'required' => false,
        'user_defined' => true,
        'searchable' => false,
        'filterable' => false,
        'comparable' => false,
        'visible_on_front' => false,
        'unique' => false,
        'apply_to' => 'virtual',
        'is_configurable' => false,
    )
);

$newSet = Mage::getModel('eav/entity_attribute_set');
$newSet->setEntityTypeId($entity_type_id);
$newSet->setAttributeSetName("Video");
$newSet->save();
$newSet->initFromSkeleton($entity_type_id);
$newSet->save();
$installer->addAttributeToSet(Mage_Catalog_Model_Product::ENTITY, "Video", "General", "sl_video_id");

$installer->endSetup();