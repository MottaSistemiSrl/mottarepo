<?php
/**
 * @category Bitbull
 * @package  Bitbull_BancaSellaPro
 * @author   Mirko Cesaro <mirko.cesaro@bitbull.it>
 */
abstract class Bitbull_BancaSellaPro_Model_Webservice_Abstract extends Mage_Core_Model_Abstract{

    protected $url_home;

    /**
     * metodo che imposta l'url dell'webservice
     * @param $url
     */
    public function setBaseUrl($url){
        $this->url_home = $url;
    }

    abstract public function getWSUrl();
}