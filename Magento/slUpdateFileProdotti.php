<?php

ini_set("memory_limit", 99999999999999);
require_once('app/Mage.php');
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));

//definisco il file che verrà dato in pasto a magento per l'import
$importFile = Mage::getBaseDir('base') . DS . 'var' . DS . 'import' . DS . 'articoli_parsed_update.csv';



//apro il file in scrittura
$fh = fopen($importFile, "w");

//scrivo l'intestazione del file
fputcsv($fh, array("sku",  "store","type","links_purchased_separately", "links"));

//leggo la directory di partenza dove troverò all'interno le cartelle dei singoli modelli
$dir = new DirectoryIterator('import');

$sizeToCheck = Array();
foreach ($dir as $fileinfo) {
    //verifico che sia una directory
    if ($fileinfo->isDir() && !$fileinfo->isDot()) {
        // se è una directory incomincio la lavorazione dei file al suo interno
        $dirToUse =  $fileinfo->getFilename();
        //leggo gli xml all'interno della directory
        $staticFields = array(0, "", "Default", "simple", "Default Category", "base", 2, 1, 1);
        foreach(glob('import/' . $dirToUse . "/*xml") as $filename) {
            //carico il contenuto dell'xml e lo elaboro
            if(simplexml_load_file($filename)){
                $xml_file = simplexml_load_file($filename);
                //carico i dati che mi servono per produrre il file compatibile con Magento
                $sku = $xml_file->product_id;

                //copio il file sotto la cartella media/import
                if(!copy($importFile = Mage::getBaseDir('base') . DS . 'import' . DS . $dirToUse . DS . $xml_file->files->file[2], $importFile = Mage::getBaseDir('base') . DS . 'media' . DS . 'import' . DS . substr(strrchr($xml_file->files->file[2], "/"), 1))){
                    echo 'Impossibile copiare file ' . $filename;
                }
                $filepdf =  substr(strrchr($xml_file->files->file[2], "/"), 1);
                //echo $filepdf;
                //scrivo la riga principale del prodotto
                $sampleFile = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_WEB) . "guida_pdf.html";
                if($filepdf != "") {
                    $firstRow = array($sku, "admin","downloadable","0","file:" . Mage::getBaseDir('base') . DS . "media/import/" . $filepdf . ',sort_order:,title:' . $sku . ',sample:' . $sampleFile . ',is_shareable:config,number_of_downloads:0');
                    fputcsv($fh, $firstRow);
                }
                //riga seconda immagine

            }
        }
    }
}

//chiudo il file
fclose($fh);

foreach ($sizeToCheck as $_sizeToCheck){
    checkIfInsertAttribute('size',$_sizeToCheck);

}

function checkIfInsertAttribute($modello,$valore) {
    require_once('app/Mage.php');
    Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));

    $attributeCode = $modello;
    $attribute = Mage::getModel('eav/config')->getAttribute('catalog_product', $attributeCode);
    if ($attribute->usesSource()) {
        $options = $attribute->getSource()->getAllOptions(false);
    }
    $existingValues = array();
    foreach($options as $value)
    {
        $existingValues[] = strtolower($value['label']);
    }

    $attr_model = Mage::getModel('catalog/resource_eav_attribute');
    $attr = $attr_model->loadByCode('catalog_product', $attributeCode);
    $attr_id = $attr->getAttributeId();


    if(!in_array(strtolower($valore), $existingValues, false))
    {
        $option = array();
        $option['value']['xxx'][0] = $valore;
        $option['value']['xxx'][1] = $valore;
        $option['attribute_id'] = $attr_id;


        $setup = new Mage_Eav_Model_Entity_Setup('core_setup');
        $setup->addAttributeOption($option);
    }
}

