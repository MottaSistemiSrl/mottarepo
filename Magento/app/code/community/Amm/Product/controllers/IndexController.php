<?php

class Amm_Product_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Index action
     */
    
    protected function _getSession()
    {		
        return Mage::getSingleton('customer/session');
    }

    public function indexAction()
    {
     	$session = $this->_getSession();

        Mage::helper('product')->checkTable();

    	if(!$session->isLoggedIn()){
            Mage::getSingleton('customer/session')->setBeforeAuthUrl(Mage::getUrl('*'));
    		$this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
    	}else{
    		$this->loadLayout();

                $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
                if ($navigationBlock) {
                    $navigationBlock->setActive('product');
                }

            $this->renderLayout();
        }
    }

    public function editAction()
    {
        $param = $this->getRequest()->getParams();
        if($param['ep']==0){
            $this->_redirectUrl(Mage::getBaseUrl('link').'product/');
        }else{ 
            $session = $this->_getSession();
            if(!$session->isLoggedIn()){
                $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
            }else{
                $this->loadLayout();

                    $navigationBlock = $this->getLayout()->getBlock('customer_account_navigation');
                    if ($navigationBlock) {
                        $navigationBlock->setActive('product');
                    }

                $this->renderLayout();
            }
        }
    }

    public function deleteAction()
    {
        $param = $this->getRequest()->getParams();
        if($param['p']!=0){
            $session = $this->_getSession();
            if(!$session->isLoggedIn()){
                $this->_redirectUrl(Mage::helper('customer')->getLoginUrl());
            }else{
                
                $session_array = $session->toArray();
                $result = Mage::helper('product')->deleteProduct($session_array['id'],$param['p']);
                //var_dump($result);
            }
        }

        $this->_redirectUrl(Mage::getBaseUrl('link').'product/');
    }

    public function uploadAction()
    {
        try {
            $uploader = new Mage_Core_Model_File_Uploader('image');
            $uploader->setAllowedExtensions(array('jpg','jpeg','gif','png'));
            $uploader->addValidateCallback('catalog_product_image',
                Mage::helper('catalog/image'), 'validateUploadFile');
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save(
                Mage::getSingleton('catalog/product_media_config')->getBaseTmpMediaPath()
            );

            Mage::dispatchEvent('catalog_product_gallery_upload_image_after', array(
                'result' => $result,
                'action' => $this
            ));

            /**
             * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
             */
            $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
            $result['path'] = str_replace(DS, "/", $result['path']);

            $result['url'] = Mage::getSingleton('catalog/product_media_config')->getTmpMediaUrl($result['file']);
            $result['file'] = $result['file'] . '.tmp';
            $result['cookie'] = array(
                'name'     => session_name(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain()
            );

        } catch (Exception $e) {
            $result = array(
                'error' => $e->getMessage(),
                'errorcode' => $e->getCode());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }

    public function additionalfieldsAction(){
        $param = $this->getRequest()->getParams();
        $result = Mage::helper('product')->getAdditionalFieldsProduct($param['setp'],$param['entid']);
        $this->getResponse()->setBody($result);
    }

    protected function _processDownload($resource, $resourceType)
    {
        $helper = Mage::helper('downloadable/download');
        /* @var $helper Mage_Downloadable_Helper_Download */

        $helper->setResource($resource, $resourceType);

        $fileName       = $helper->getFilename();
        $contentType    = $helper->getContentType();

        $this->getResponse()
            ->setHttpResponseCode(200)
            ->setHeader('Pragma', 'public', true)
            ->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true)
            ->setHeader('Content-type', $contentType, true);

        if ($fileSize = $helper->getFilesize()) {
            $this->getResponse()
                ->setHeader('Content-Length', $fileSize);
        }

        if ($contentDisposition = $helper->getContentDisposition()) {
            $this->getResponse()
                ->setHeader('Content-Disposition', $contentDisposition . '; filename='.$fileName);
        }

        $this->getResponse()
            ->clearBody();
        $this->getResponse()
            ->sendHeaders();

        $helper->output();
    }

    /**
     * Download link action
     *
     */
    public function linkAction()
    {
        $linkId = $this->getRequest()->getParam('id', 0);
        $link = Mage::getModel('downloadable/link')->load($linkId);
        if ($link->getId()) {
            $resource = '';
            $resourceType = '';
            if ($link->getLinkType() == Mage_Downloadable_Helper_Download::LINK_TYPE_URL) {
                $resource = $link->getLinkUrl();
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_URL;
            } elseif ($link->getLinkType() == Mage_Downloadable_Helper_Download::LINK_TYPE_FILE) {
                $resource = Mage::helper('downloadable/file')->getFilePath(
                    Mage_Downloadable_Model_Link::getBasePath(), $link->getLinkFile()
                );
                $resourceType = Mage_Downloadable_Helper_Download::LINK_TYPE_FILE;
            }
            try {
                $this->_processDownload($resource, $resourceType);
            } catch (Mage_Core_Exception $e) {
                $this->_getCustomerSession()->addError(Mage::helper('downloadable')->__('An error occurred while getting the requested content.'));
            }
        }
        exit(0);
    }

    public function downloadableuploadAction()
    {
        $type = $this->getRequest()->getParam('type');
        $tmpPath = '';
        if ($type == 'samples') {
            $tmpPath = Mage_Downloadable_Model_Sample::getBaseTmpPath();
        } elseif ($type == 'links') {
            $tmpPath = Mage_Downloadable_Model_Link::getBaseTmpPath();
        } elseif ($type == 'link_samples') {
            $tmpPath = Mage_Downloadable_Model_Link::getBaseSampleTmpPath();
        }
        $result = array();
        try {
            $uploader = new Mage_Core_Model_File_Uploader($type);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $result = $uploader->save($tmpPath);

            /**
             * Workaround for prototype 1.7 methods "isJSON", "evalJSON" on Windows OS
             */
            $result['tmp_name'] = str_replace(DS, "/", $result['tmp_name']);
            $result['path'] = str_replace(DS, "/", $result['path']);

            if (isset($result['file'])) {
                $fullPath = rtrim($tmpPath, DS) . DS . ltrim($result['file'], DS);
                Mage::helper('core/file_storage_database')->saveFile($fullPath);
            }

            $result['cookie'] = array(
                'name'     => session_name(),
                'value'    => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path'     => $this->_getSession()->getCookiePath(),
                'domain'   => $this->_getSession()->getCookieDomain()
            );
        } catch (Exception $e) {
            $result = array('error'=>$e->getMessage(), 'errorcode'=>$e->getCode());
        }

        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode($result));
    }
}