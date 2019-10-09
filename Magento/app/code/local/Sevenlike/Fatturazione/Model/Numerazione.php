<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 03/09/13
 * Time: 13.16
 * To change this template use File | Settings | File Templates.
 */

class Sevenlike_Fatturazione_Model_Numerazione extends Mage_Core_Model_Abstract{

    const XML_CONFIG_PATH = 'fatturazione/#TIPO_DOC';

    protected function _construct()
    {
        $this->_init("fatturazione/numerazione");
    }

    public function getNextIncrementId($tipo_doc,$store_id,$update = false){

        $currYear = date('Y');

        $idItem = $this->getCollection()->addFieldToFilter('tipo_doc',$tipo_doc)->addFieldToFilter('store_id',$store_id)->getFirstItem();
        if($tipo_doc == 'fattura' )
        {
            $idParallelItem = $this->getCollection()->addFieldToFilter('tipo_doc','creditmemo')->addFieldToFilter('store_id',$store_id)->getFirstItem();
        }elseif($tipo_doc == 'creditmemo'){
            $idParallelItem = $this->getCollection()->addFieldToFilter('tipo_doc','fattura')->addFieldToFilter('store_id',$store_id)->getFirstItem();
        }


        if(!$idItem->getId()){

            $nextId = (int)$this->getStoreConfig('numero_iniziale',$tipo_doc,$store_id);
            $idItem->setCreatedAt(now());
            $idItem->setTipoDoc($tipo_doc);
            $idItem->setStoreId($store_id);
        }else{
            if($idItem->getAnnoCorrente()!=$currYear){
                $nextId = 1;
            }else{
                if($tipo_doc == 'fattura' || $tipo_doc == 'creditmemo' ){
                    $nextId = max($idItem->getUltimoId(),$idParallelItem->getUltimoId()) + 1;
                } else {
                    $nextId = $idItem->getUltimoId()+1;
                }

            }
        }



        $nextIdStr = str_pad($nextId,5,0,STR_PAD_LEFT);
        $formato = $this->getStoreConfig('formato',$tipo_doc,$store_id);
        $toRet = str_replace('$A',$currYear,$formato);
        $toRet = str_replace('$S',$store_id,$toRet);
        $toRet = str_replace('$N',$nextIdStr,$toRet);
        if($update){
            $idItem->setAnnoCorrente($currYear);
            $idItem->setUltimoId($nextId);
            $idItem->setUltimoNumeroGenerato($toRet);
            $idItem->save();
        }
        return $toRet;
    }

    public function getStoreConfig($config,$tipo_doc,$store_id){
        $path = str_replace('#TIPO_DOC',$tipo_doc,self::XML_CONFIG_PATH).'/'.$config;
        return Mage::getStoreConfig($path,$store_id);
    }


}