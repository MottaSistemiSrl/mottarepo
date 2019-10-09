<?php
class Sevenlike_Mediaserver_Model_Observer
{
    /**
     * Reading which video it's been selected from the "video" app
     * @param Varien_Event_Observer $observer
     */
    public function catalogProductSaveBefore(Varien_Event_Observer $observer)
    {
        $db = Mage::getSingleton('core/resource')->getConnection('core_read');
        $product = $observer->getProduct();
        $video_id = $db->fetchOne("SELECT * FROM sl_mediaserver_videos where product_id=?", $product->getId());
        $product->setSlVideoId($video_id);
    }
}