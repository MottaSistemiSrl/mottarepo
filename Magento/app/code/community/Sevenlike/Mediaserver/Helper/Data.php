<?php
class Sevenlike_Mediaserver_Helper_Data extends Mage_Core_Helper_Abstract
{
	public function getMediaServerUrl()
	{
	    $host = parse_url(Mage::getUrl('/'));
        $host = $host["host"];
		$host = "mediaserver.{$host}";
		$host = str_replace("www.", "", $host);
		return $host;
	}
	
	public function isVideoBought($product_id)
	{
		//TODO: check abbonamento e return true
		
		if (!@$GLOBALS["_sl_bought_video"]) {
			Mage::app()->getLayout()->createBlock('mediaserver/customer_products_list');
		}
		
		if (empty($GLOBALS["_sl_bought_video"])) return false;
		return in_array($product_id, array_keys($GLOBALS["_sl_bought_video"]));
	}

	public function hasProductCompleteVideo($product_id)
	{
		$db = Mage::getSingleton('core/resource')->getConnection("core_read");
		return $db->fetchOne("SELECT COUNT(*) FROM sl_mediaserver_videos WHERE product_id=? AND video_path IS NOT NULL", $product_id);
	}
}