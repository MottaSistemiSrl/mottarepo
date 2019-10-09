<?php
class Amm_Product_Adminhtml_IndexController extends Mage_Adminhtml_Controller_Action
{

	protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu('adminhtml/adminhtml')->_addBreadcrumb('product Manager','product Manager');
       return $this;
    }

	public function indexAction()
	{
	    $this->_initAction()->renderLayout();
	}

	public function editAction()
      {
      	$id = $this->getRequest()->getParam('id', null);
      	$url=Mage::getBaseUrl ()."admin/catalog_product/edit/id/".Mage::helper('product')->getIdProduct($id)."/key/97f1846ceb7058f32e09535060a4627f/";
      	//echo $url;
      	//exit();
		$this->getResponse()->setRedirect($url);
      	//$this->_redirect('/admin/catalog_product/edit/id/'.$id.'/');
      }

}