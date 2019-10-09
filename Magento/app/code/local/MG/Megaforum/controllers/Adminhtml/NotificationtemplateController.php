<?php

class MG_Megaforum_Adminhtml_NotificationtemplateController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("megaforum/notificationtemplate")->_addBreadcrumb(Mage::helper("adminhtml")->__("Notificationtemplate  Manager"),Mage::helper("adminhtml")->__("Notificationtemplate Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Megaforum"));
			    $this->_title($this->__("Manager Notificationtemplate"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Megaforum"));
				$this->_title($this->__("Notificationtemplate"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("megaforum/notificationtemplate")->load($id);
				if ($model->getId()) {
					Mage::register("notificationtemplate_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("megaforum/notificationtemplate");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Notificationtemplate Manager"), Mage::helper("adminhtml")->__("Notificationtemplate Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Notificationtemplate Description"), Mage::helper("adminhtml")->__("Notificationtemplate Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("megaforum/adminhtml_notificationtemplate_edit"))->_addLeft($this->getLayout()->createBlock("megaforum/adminhtml_notificationtemplate_edit_tabs"));
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
		$this->_title($this->__("Notificationtemplate"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("megaforum/notificationtemplate")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("notificationtemplate_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("megaforum/notificationtemplate");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Notificationtemplate Manager"), Mage::helper("adminhtml")->__("Notificationtemplate Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Notificationtemplate Description"), Mage::helper("adminhtml")->__("Notificationtemplate Description"));


		$this->_addContent($this->getLayout()->createBlock("megaforum/adminhtml_notificationtemplate_edit"))->_addLeft($this->getLayout()->createBlock("megaforum/adminhtml_notificationtemplate_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("megaforum/notificationtemplate")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Notificationtemplate was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setNotificationtemplateData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setNotificationtemplateData($this->getRequest()->getPost());
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
						$model = Mage::getModel("megaforum/notificationtemplate");
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
				$ids = $this->getRequest()->getPost('notificationtemplate_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("megaforum/notificationtemplate");
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
			$fileName   = 'notificationtemplate.csv';
			$grid       = $this->getLayout()->createBlock('megaforum/adminhtml_notificationtemplate_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'notificationtemplate.xml';
			$grid       = $this->getLayout()->createBlock('megaforum/adminhtml_notificationtemplate_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
