<?php

ini_set("memory_limit", 99999999999999);
require_once('app/Mage.php');
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));

//definisco il file che verrà dato in pasto a magento per l'import
$importFile = Mage::getBaseDir('base') . DS . 'var' . DS . 'import' . DS . 'articoli_parsed.csv';


//definisco i tipi di immagine che possono essere caricati
$image_extensions_allowed = array('jpg', 'jpeg', 'png', 'gif');
//apro il file in scrittura
$fh = fopen($importFile, "w");

//scrivo l'intestazione del file
fputcsv($fh, array("sku", "size", "pagamento", "tipologia", "livello", "status", "name", "description", "category_ids", "price", "store", "attribute_set", "type", "_root_category", "_product_websites", "tax_class_id", "use_config_manage_stock", "visibility", "image", "small_image", "thumbnail", "_media_attribute_id", "_media_image", "_media_position", "_media_is_disabled", "_media_lable","media_gallery","is_in_stock"));

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
                $name = $xml_file->name;
                $description =  $xml_file->description;
                $sku = $xml_file->product_id;
                //ciclo all'interno dei size disponibili
                $_sizes = array();
                foreach($xml_file->size as $size)
                {
                    if (count($size) > 0) {
                        foreach($size as $key => $_size) {
                            if($_size != ''){
                                array_push($_sizes,(int)$_size);
                            }

                        }
                    }
                }
                // definisco il size da scrivere nel file nel formato minsize-maxsize
                $sizeToWrite = min($_sizes) . "-" . max($_sizes);
                if (!in_array($sizeToWrite,$sizeToCheck )) {
                    array_push($sizeToCheck, $sizeToWrite);
                }

                //definisco la categoria leggendo dal file il gender
                $category = $xml_file->gender;

                switch (strtolower(trim($category))) {
                    case 'neuheiten':
                        $category = "Shop & Cartamodelli/Novità";
                        break;
                    case 'anfänger':
                        $category = "Shop & Cartamodelli/Principiante";
                        break;
                    case 'damen':
                        $category = "Shop & Cartamodelli/Donna";
                        break;
                    case 'herren':
                        $category = "Shop & Cartamodelli/Uomo";
                        break;
                    case 'kinder':
                        $category = "Shop & Cartamodelli/Kids";
                        break;
                    case 'wohnen':
                        $category = "Shop & Cartamodelli/Accessori per arredare";
                        break;
                    case 'historische kostüme':
                        $category = "Shop & Cartamodelli/Costumi storici";
                        break;
                    case 'fasching':
                        $category = "Shop & Cartamodelli/Carnevale";
                        break;
                    case 'schnitte aus dem heft':
                        $category = "Shop & Cartamodelli/Cartamodelli dalla rivista";
                        break;
                    case 'schnitte aus sonderheften':
                        $category = "Shop & Cartamodelli/Cartamodelli dagli speciali";
                        break;
                    case 'katalogschnitte':
                        $category = "Shop & Cartamodelli/Cartamodelli singoli da catalogo";
                        break;
                    case 'mädchen':
                        $category = "Shop & Cartamodelli/Kids/Bambine";
                        break;
                    case 'jungen':
                        $category = "Shop & Cartamodelli/Kids/Bambini";
                        break;
                    case 'creativ':
                        $category = "Shop & Cartamodelli/Kids/Accessori per bimbi";
                        break;
                }

                //carico l'id della categoria che va a sostiture la string
                $categorie = explode("/",$category);
                $categorieIds = "";
                foreach($categorie as $_categoria){
                    $_category = Mage::getModel('catalog/category')->loadByAttribute('name', $_categoria);
                    if($_category){
                        $categorieIds = $categorieIds . $_category->getId() . ",";
                    }
                }
                $categorieIds = substr($categorieIds,0,strlen($categorieIds)-1);

                $difficulty = $xml_file->difficulty;
                $price = $xml_file->pricemodel->price;

                //ciclo su tutti i file presenti nel prodotto caricandomi solamente le immagini
                $_images = array();
                $image1 = "";
                $image2 = "";
                $imageBluePrint = "";
                //immagine principale
                $ext = strtolower(substr(strrchr($xml_file->files->file[0], "."), 1));
                if(in_array($ext, $image_extensions_allowed))
                {
                    //copio il file sotto la cartella media/import
                    if(!copy($importFile = Mage::getBaseDir('base') . DS . 'import' . DS . $dirToUse . DS . $xml_file->files->file[0], $importFile = Mage::getBaseDir('base') . DS . 'media' . DS . 'import' . DS . substr(strrchr($xml_file->files->file[0], "/"), 1))){
                        echo 'Impossibile copiare file ' . $xml_file->files->file[0];
                    }
                    $image1 =  substr(strrchr($xml_file->files->file[0], "/"), 1);
                }
                //secnda immagine
                $ext = strtolower(substr(strrchr($xml_file->files->file[1], "."), 1));
                if(in_array($ext, $image_extensions_allowed))
                {
                    if(!copy($importFile = Mage::getBaseDir('base') . DS . 'import' . DS . $dirToUse . DS . $xml_file->files->file[1], $importFile = Mage::getBaseDir('base') . DS . 'media' . DS . 'import' . DS . substr(strrchr($xml_file->files->file[1], "/"), 1))){
                        echo 'Impossibile copiare file ' . $xml_file->files->file[1];
                    }
                    $image2 =  substr(strrchr($xml_file->files->file[1], "/"), 1);
                }

                //immagine blueprint
                $ext = strtolower(substr(strrchr($xml_file->files->file[4], "."), 1));
                if(in_array($ext, $image_extensions_allowed))
                {
                    if(!copy($importFile = Mage::getBaseDir('base') . DS . 'import' . DS . $dirToUse . DS . $xml_file->files->file[4], $importFile = Mage::getBaseDir('base') . DS . 'media' . DS . 'import' . DS . substr(strrchr($xml_file->files->file[4], "/"), 1))){
                        echo 'Impossibile copiare file ' . $xml_file->files->file[4];
                    }
                    $imageBluePrint =  substr(strrchr($xml_file->files->file[4], "/"), 1);;
                }

                //scrivo la riga principale del prodotto
                $firstRow = array($sku, $sizeToWrite, "A pagamento", "scaricabile", $difficulty, 1, $name, $description, $categorieIds, $price, "", "Default", "downloadable", "Default Category", "base", "2", "1", "4",$image1,$image1,$image1,"88",$image1, "0","0",$sku,$image2 . "::" . $sku . ";" . $imageBluePrint . "::back","1");
                fputcsv($fh, $firstRow);
                //riga seconda immagine
                if($image2 != ""){
                    $secondRow = array("","","","","","","","","","","","","","","","","","","","","","88",$image2,"1","0",$sku);
                    //fputcsv($fh, $secondRow);
                }
                if($imageBluePrint != ""){
                    //riga immagine blue print
                    $thirdRow = array("","","","","","","","","","","","","","","","","","","","","","88",$imageBluePrint,"2","0","back");
                    //fputcsv($fh, $thirdRow);
                }
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

