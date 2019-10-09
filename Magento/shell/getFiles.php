<?php
require_once 'abstract.php';
class Sevenlike_Shell_CancelOrders extends Mage_Shell_Abstract
{
    public function run()
    {
        $coreResource = Mage::getSingleton('core/resource') ;
        $write = $coreResource->getConnection('core_write');
	$q = "select distinct link_file from downloadable_link";
	$orders = $write->fetchCol($q);
       echo "\n";
	foreach($orders as $orderId){
		$f = Mage::getBaseDir('media')."/downloadable/files/links".$orderId;
		if(!file_exists($f)){
		 file_put_contents('media.txt',"$f\n",FILE_APPEND);
		$f1 = explode("/",$orderId);
		$f1 = array_pop($f1);
		$f1 =  Mage::getBaseDir('media')."/import/".$f1;
		 if(file_exists($f1)){
                 file_put_contents('mediaImport.txt',"$f1\n",FILE_APPEND);
		 copy($f1,$f);
}		

		}
		
	}	
     }
}

$shell = new Sevenlike_Shell_CancelOrders();
$shell->run();
