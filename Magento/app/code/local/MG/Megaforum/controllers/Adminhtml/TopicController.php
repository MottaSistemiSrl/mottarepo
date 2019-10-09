<?php

class MG_Megaforum_Adminhtml_TopicController extends Mage_Adminhtml_Controller_Action
{
		protected function _initAction()
		{
				$this->loadLayout()->_setActiveMenu("megaforum/topic")->_addBreadcrumb(Mage::helper("adminhtml")->__("Topic  Manager"),Mage::helper("adminhtml")->__("Topic Manager"));
				return $this;
		}
		public function indexAction() 
		{
			    $this->_title($this->__("Megaforum"));
			    $this->_title($this->__("Manager Topic"));

				$this->_initAction();
				$this->renderLayout();
		}
		public function editAction()
		{			    
			    $this->_title($this->__("Megaforum"));
				$this->_title($this->__("Topic"));
			    $this->_title($this->__("Edit Item"));
				
				$id = $this->getRequest()->getParam("id");
				$model = Mage::getModel("megaforum/topic")->load($id);
				if ($model->getId()) {
					Mage::register("topic_data", $model);
					$this->loadLayout();
					$this->_setActiveMenu("megaforum/topic");
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Topic Manager"), Mage::helper("adminhtml")->__("Topic Manager"));
					$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Topic Description"), Mage::helper("adminhtml")->__("Topic Description"));
					$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
					$this->_addContent($this->getLayout()->createBlock("megaforum/adminhtml_topic_edit"))->_addLeft($this->getLayout()->createBlock("megaforum/adminhtml_topic_edit_tabs"));
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
		$this->_title($this->__("Topic"));
		$this->_title($this->__("New Item"));

        $id   = $this->getRequest()->getParam("id");
		$model  = Mage::getModel("megaforum/topic")->load($id);

		$data = Mage::getSingleton("adminhtml/session")->getFormData(true);
		if (!empty($data)) {
			$model->setData($data);
		}

		Mage::register("topic_data", $model);

		$this->loadLayout();
		$this->_setActiveMenu("megaforum/topic");

		$this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Topic Manager"), Mage::helper("adminhtml")->__("Topic Manager"));
		$this->_addBreadcrumb(Mage::helper("adminhtml")->__("Topic Description"), Mage::helper("adminhtml")->__("Topic Description"));


		$this->_addContent($this->getLayout()->createBlock("megaforum/adminhtml_topic_edit"))->_addLeft($this->getLayout()->createBlock("megaforum/adminhtml_topic_edit_tabs"));

		$this->renderLayout();

		}
		public function saveAction()
		{

			$post_data=$this->getRequest()->getPost();


				if ($post_data) {

					try {

						

						$model = Mage::getModel("megaforum/topic")
						->addData($post_data)
						->setId($this->getRequest()->getParam("id"))
						->save();

						Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Topic was successfully saved"));
						Mage::getSingleton("adminhtml/session")->setTopicData(false);

						if ($this->getRequest()->getParam("back")) {
							$this->_redirect("*/*/edit", array("id" => $model->getId()));
							return;
						}
						$this->_redirect("*/*/");
						return;
					} 
					catch (Exception $e) {
						Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
						Mage::getSingleton("adminhtml/session")->setTopicData($this->getRequest()->getPost());
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
						$model = Mage::getModel("megaforum/topic");
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
				$ids = $this->getRequest()->getPost('topic_ids', array());
				foreach ($ids as $id) {
                      $model = Mage::getModel("megaforum/topic");
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
			$fileName   = 'topic.csv';
			$grid       = $this->getLayout()->createBlock('megaforum/adminhtml_topic_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
		} 
		/**
		 *  Export order grid to Excel XML format
		 */
		public function exportExcelAction()
		{
			$fileName   = 'topic.xml';
			$grid       = $this->getLayout()->createBlock('megaforum/adminhtml_topic_grid');
			$this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
		}
}
