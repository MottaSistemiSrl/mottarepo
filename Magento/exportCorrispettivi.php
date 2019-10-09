<?php

ini_set("memory_limit", 99999999999999);
require_once('app/Mage.php');
Mage::app()->setCurrentStore(Mage::getModel('core/store')->load(Mage_Core_Model_App::ADMIN_STORE_ID));


$importFile = Mage::getBaseDir('base') . DS . 'var' . DS . 'export' . DS . 'export_corrispettivi.csv';



//apro il file in scrittura
$fh = fopen($importFile, "w");

//scrivo l'intestazione del file
fputcsv($fh, array("data", "increment_id", "metodo di pagamento", "tipo", "imponibile","iva", "impobibile excl iva", "aliquota", "valuta", "sconto", "regione", "provincia"));

$modelCorrispettivi = Mage::helper('registrocorrispettivi');

$collection = Mage::getModel("registrocorrispettivi/registrocorrispettivi")->getCollection();


foreach($collection as $corrispettivo){
    $row = array($corrispettivo["data_comp"], $corrispettivo["increment_id"], $corrispettivo["label_pagamento"], $corrispettivo["tipo"], $corrispettivo["imponibile"],$corrispettivo["iva"],
        $corrispettivo["imponibile_excl_tax"], $corrispettivo["aliquota"], $corrispettivo["valuta"], $corrispettivo["sconto"], $corrispettivo["regione"], $corrispettivo["provincia"]);
    fputcsv($fh, $row);
}

//chiudo il file
fclose($fh);


//estraggo i totali
$importFile = Mage::getBaseDir('base') . DS . 'var' . DS . 'export' . DS . 'export_corrispettivi_totali.csv';

//apro il file in scrittura
$fh = fopen($importFile, "w");

$collection = Mage::getModel("registrocorrispettivi/registrocorrispettivi_totali")->getCollection();
fputcsv($fh, array("codice paese", "data", "n doc", "descrizione", "aliquota","valuta", "fattore valuta", "importo",
    "iva", "importo netto", "metodo pagamento", "sconto"));
foreach($collection as $corrispettivo){
    $row = array($corrispettivo["cod_paese"], $corrispettivo["data_comp"], $corrispettivo["n_doc"], $corrispettivo["descrizione"],
        $corrispettivo["aliquota"],$corrispettivo["valuta"],
        $corrispettivo["fattore_valuta"], $corrispettivo["importo"], $corrispettivo["iva"], $corrispettivo["importo_netto"],
        $corrispettivo["label_pagamento"], $corrispettivo["discount_amount"]);
    fputcsv($fh, $row);
}

fclose($fh);


$emailContent = "Export corrispettivi in allegato";
$mail = new Zend_Mail();
$mail->setType(Zend_Mime::MULTIPART_RELATED);
$mail->setBodyHtml($emailContent);
$mail->setFrom('info@burdastyle.it', 'Burdastyle');
$mail->addTo('andreanaggi@pieronitalia.it', 'Andrea Naggi');
$mail->addTo('simonetta.notargiacomo@burdaitalia.it', 'Simonetta Notargiacomo');
$mail->addTo('marta.china@sevenlike.com','Marta China');
$mail->setSubject('Export corrispettivi');
$dir = Mage::getBaseDir();
$path = Mage::getBaseDir('var') . DS . 'export' . DS . 'export_corrispettivi_totali.csv';
$file = $mail->createAttachment(file_get_contents($path));
$file ->type        = 'text/csv';
$file ->disposition = Zend_Mime::DISPOSITION_INLINE;
$file ->encoding    = Zend_Mime::ENCODING_BASE64;
$file ->filename    = 'export_corrispettivi_totali.csv';
$path = Mage::getBaseDir('var') . DS . 'export' . DS . 'export_corrispettivi.csv';
$file = $mail->createAttachment(file_get_contents($path));
$file ->type        = 'text/csv';
$file ->disposition = Zend_Mime::DISPOSITION_INLINE;
$file ->encoding    = Zend_Mime::ENCODING_BASE64;
$file ->filename    = 'export_corrispettivi.csv';

$mail->send();


