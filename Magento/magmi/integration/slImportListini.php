<?php
/**
 * Created by PhpStorm.
 * User: Andrea Becchio
 * Date: 13/04/15
 * Time: 11.30
 */
ini_set("memory_limit", "512M");
set_time_limit(0);

/*
 * INIZIO ESECUZIONE
 */
$prRoot = dirname(dirname(dirname(__FILE__)));
require_once(dirname(dirname(__FILE__))."/inc/magmi_defs.php");

require_once(dirname(__FILE__) . "/inc/magmi_datapump.php");
/**
 * create a Product import Datapump using Magmi_DatapumpFactory
 */
$dp=Magmi_DataPumpFactory::getDataPumpInstance("productimport");

require_once $prRoot . '/app/Mage.php';
header("Content-Type: text/plain; charset=UTF-8");
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));
$import_dir = $prRoot . '/var/import/listini';
$bck_dir = $prRoot . '/var/import/bck';
$lock_dir =$prRoot . '/var/locks';
$store_id = 1;
$website_id = 1;

// NON MODIFICARE OLTRE QUESTA LINEA


ob_implicit_flush();
$lock = fopen($lock_dir."/.lock_importer_listini", "w");
if (flock($lock, LOCK_EX|LOCK_NB)){

// lettura file CSV da importare
    $to_import = scandir($import_dir);
    array_shift($to_import);
    array_shift($to_import);
    $args = getopt("",array('scadenza_special:'));
    if($args['scadenza_special']){
        $scadenza = $args['scadenza_special'];
    }else{
        $scadenza = date('Y-m-d');
    }
    status_message("Inizio import listini");
    if (empty($to_import)) {
        echo "\n".date('d/m/Y H:i:s')."Nessun file da importare\n";
        die();
    }

//la procedura è efficiente se il file dei listiti è ordinato per sku
    foreach ($to_import as $csv_filename) {
        $fp = fopen("{$import_dir}/$csv_filename", 'r');
        $csv_row = fgetcsv($fp); // scartiamo la prima riga, contiene i nomi di campo
        $skuIndex = array_search("sku",$csv_row);
        $priceIndex = array_search("price",$csv_row);
        $costIndex = array_search("cost",$csv_row);
        $msrpIndex = array_search("prezzo_listino_consigliato",$csv_row);
        status_message("Inizio Parsing file {$csv_filename}");

        $data = date("Ymd_His");
        $dp->beginImportSession("default","update");
        $product = array();
        while ($csv_row = fgetcsv($fp)) {
            if(strlen(trim($csv_row[$skuIndex]))==0){
                continue;
            }
            $product['sku'] = trim($csv_row[$skuIndex]);
            $product['store'] = 'admin';
            $product['price']=trim(str_replace(",",".",$csv_row[$priceIndex]));
            $product['msrp']=trim(str_replace(",",".",$csv_row[$msrpIndex]));
            $product['prezzo_listino_consigliato']=trim(str_replace(",",".",$csv_row[$msrpIndex]));
            $product['cost']=trim(str_replace(",",".",$csv_row[$costIndex]));
            $product['listino_aggiornato']=1;
            $product['special_to_date']= $scadenza;
            //print_r($product);
            $a = $dp->ingest($product);
            //print_r($a);
            $i +=1;
            if($i%100 == 0){
                      status_message("{$i} prodotti importati");

            }

        }
        fclose($fp);
        $orfile = $import_dir . "/".$csv_filename;
        $mvDir= $bck_dir ."/".$csv_filename;
        exec(" mv {$orfile} {$mvDir}", $tmp, $check);
        if ($check != 0) {
            status_message("Impossibile spostare il file [{$csv_filename}");
        }
        //     fclose($fileNew);
        status_message("Parsing del file {$csv_filename} terminata.");
    }
    $dp->endImportSession();
}else{
    status_message("Altro import in corso");


}


function status_message($message)
{
    date_default_timezone_set('CET');
    echo date('d/m/Y H:i:s') .' - '. $message ."\n";
}
