<?php

class MG_Megaforum_Adminhtml_ForumController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("megaforum/forum")->_addBreadcrumb(Mage::helper("adminhtml")->__("Forum  Manager"),Mage::helper("adminhtml")->__("Forum Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Megaforum"));
			    $this->_title($this->__("Manager Forum"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Megaforum"));
				$this->_title($this->__("Forum"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("megaforum/forum")->load($id);
				if ($model->getId()) {
					Mage::register("forum_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("megaforum/forum");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Forum Manager"), Mage::helper("adminhtml")->__("Forum Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Forum Description"), Mage::helper("adminhtml")->__("Forum Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("megaforum/adminhtml_forum_edit"))->_addLeft($this->getLayout()->createBlock("megaforum/adminhtml_forum_edit_tabs"));
					$this->renderLayout();
				} 
				else {
					Mage::getSingleton("adminhtml/session")->addError(Mage::helper("megaforum")->__("Item does not exist."));
					$this->_redirect("*/*/");
				}
		}

		public function newAction()
		{

		$this->_title($this->__("Megaforum"));
		$this->_title($this->__("Forum"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("megaforum/forum")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("forum_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("megaforum/forum");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Forum Manager"), Mage::helper("adminhtml")->__("Forum Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Forum Description"), Mage::helper("adminhtml")->__("Forum Description"));


		$this->_addContent($this->getLayout()->createBlock("megaforum/adminhtml_forum_edit"))->_addLeft($this->getLayout()->createBlock("megaforum/adminhtml_forum_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						Mage::getSingleton('admin/session')->getData();
						$user = Mage::getSingleton('admin/session');
						$userId = $user->getUser()->getId();

						$model = Mage::getModel("megaforum/forum")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->setCreatedAt(date("Y-m-d"))
						->setCreatedBy($userId)
						->save();
						
						$collection = Mage::getModel('megaforum/forumstore')->getCollection();
						$collection->addFieldToFilter('forum_id',$model->getForumId());
						
							foreach($collection as $obj) {
							 $obj->delete();
							}	
							 
						if(isset($post_data['stores'])) {
						
							if(in_array('0',$post_data['stores'])){
								$post_data['store_id'] = '0';
							    $storemodel = Mage::getSingleton('megaforum/forumstore');
								$storemodel->setForumId($model->getId())
								           ->setStoreId($post_data['store_id'])
										   ->save();
							}
							else{								
								$stores = $post_data['stores'] ;
								foreach($stores as $store){								
								$storemodel = Mage::getModel('megaforum/forumstore');
								$storemodel->setForumId($model->getId())
								           ->setStoreId($store)
										   ->save();
								}
							}							
							//unset($post_data['stores']);
						}

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Forum was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setForumData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setForumData($this->getRequest()->getPost());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					return;
					}

				}
				$this->_redirect("*/*/");
		}



		public function deleteAction()
		{
				if( $this->getRequest()->getParam("id") > 0 ) {
					try {
						$model = Mage::getModel("megaforum/forum");
						$model->setId($this->getRequest()->getParam("id"))->delete();
						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item was successfully deleted"));
						$this->_redirect("*/*/");
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						$this->_redirect("*/*/edit", array("id" => $this->getRequest()->getParam("id")));
					}
				}
				$this->_redirect("*/*/");
		}

		
		public function massRemoveAction()
		{
			try {
				$ids = $this->getRequest()->getPost('forum_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("megaforum/forum");
					  $model->setId($id)->delete();
				}
				Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Item(s) was successfully removed"));
			}
			catch (Exception $e) {
				Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
			}
			$this->_redirect('*/*/');
		}
			
		/**
		 * Export order grid to CSV format
		 */
		public function exportCsvAction()
		{
			$fileName   = 'forum.csv';
			$grid       = $this->getLayout()->createBlock('megaforum/adminhtml_forum_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'forum.xml';
			$grid       = $this->getLayout()->createBlock('megaforum/adminhtml_forum_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
