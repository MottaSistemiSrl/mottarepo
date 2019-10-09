<?php
class Sevenlike_Registrocorrispettivi_Helper_Data extends Mage_Core_Helper_Abstract
{

    const XML_CONFIG_PATH_INSERIMENTO  = "registro/general/inserimento_registro";

    public static function getAliquotaSpedizioni($store = null){
        $h = Mage::helper("tax");
        $request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false, $store);
        $includingPercent = Mage::getSingleton('tax/calculation')
            ->getRate($request->setProductClassId($h->getShippingTaxClass($store )));
        return $includingPercent;

    }
    public static function getAliquotaProdotti($store = null){
        /* @var $h Mage_Tax_Helper_Data*/
        $h = Mage::helper("tax");
        $request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false, $store);
        $includingPercent = Mage::getSingleton('tax/calculation')
            ->getRate($request->setProductClassId(2));
        return $includingPercent;

    }
    public static function getAliquotaGiftOptions($store = null){
        $h = Mage::helper("enterprise_giftwrapping");
        $request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false, $store);
        $includingPercent = Mage::getSingleton('tax/calculation')
            ->getRate($request->setProductClassId($h->getWrappingTaxClass($store )));
        return $includingPercent;

    }
    public static function getAliquotaCod($store = null){
        $h = Mage::helper("cashondelivery");
        $request = Mage::getSingleton('tax/calculation')->getRateRequest(false, false, false, $store);
        $includingPercent = Mage::getSingleton('tax/calculation')
            ->getRate($request->setProductClassId($h->getCodTaxClass($store )));
        return $includingPercent;

    }

    public function getModelloInserimento($store){
        $toRet = Mage::getStoreConfig(self::XML_CONFIG_PATH_INSERIMENTO,$store);
        if(!$toRet){
            $toRet = Sevenlike_Registrocorrispettivi_Model_Config_Inserimento::INVOICE;
        }
        return $toRet;
    }
}
	 