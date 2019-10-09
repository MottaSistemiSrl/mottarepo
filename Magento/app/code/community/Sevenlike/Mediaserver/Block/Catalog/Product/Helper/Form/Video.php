<?php
class Sevenlike_Mediaserver_Block_Catalog_Product_Helper_Form_Video extends Varien_Data_Form_Element_Text
{
    public function getHtml()
    {
        $helper = Mage::helper("mediaserver");
        $product = $this->getContainer()->getContainer()->getDataObject();
        $product_id = $product->getId();

        $host = parse_url(Mage::getUrl('/'));
        $host = "mediaserver.{$host["host"]}";
        $host = str_replace("www.", "", $host);

        if ($product_id) {
            $button  = "<button onclick='$(\"sl_video_iframe\").toggle();$(\"sl_video_iframe_close\").toggle();$(\"html-body\").scrollTo();return false;'>" . $helper->__("Apri interfaccia di gestione video") . "</button>";
            $button .= "<div id='sl_video_iframe_close' style='display:none;background:black;cursor:pointer;position:absolute;width:20px;top:10%;right:10%;z-index:10001;color:white;font-weight:bolder;text-align:center' onclick='$(\"sl_video_iframe\").toggle();$(\"sl_video_iframe_close\").toggle();'>X</div>";
            $button .= "<iframe style='position:absolute;width:80%;height:80%;top:10%;left:10%;background:white;display:none;z-index:10000' id='sl_video_iframe' src='http://$host/video/editor?product_id={$product_id}&auth_token=nkasj1929haSHDBbA277Ba226'></iframe>";
        } else {
            $button = $helper->__("E' necessario salvare il prodotto prima di poter associare un video");
        }

        $html = parent::getHtml();
        $html = preg_replace("!<input.*?/>!m", $button, $html);
        return $html;
    }
}