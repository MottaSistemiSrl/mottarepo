<?php

class Sevenlike_Registrocorrispettivi_Adminhtml_RegistrocorrispettiviController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("sales/registrocorrispettivi")->_addBreadcrumb(Mage::helper("adminhtml")->__("Dettagli Corrispettivi"),Mage::helper("adminhtml")->__("Dettagli Corrispettivi"));
        return $this;
    }
    public function indexAction()
    {
		$this->_title($this->__("Dettagli Corrispettivi"));
        $this->_initAction();

        $this->renderLayout();


    }


    public function editAction()
    {
          $this->_title($this->__("Modifica Dettaglio"));

        $id = $this->getRequest()->getParam("id");
        if(!$id){
            $this->_redirect("*/*/new");
            return;
        }
        $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi")->load($id);
        if ($model->getId()) {
            Mage::register("registrocorrispettivi_data", $model);
            $this->loadLayout();
            $this->_setActiveMenu("sales/registrocorrispettivi");
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Dettagli Corrispettivi"), Mage::helper("adminhtml")->__("Dettagli Corrispettivi"));
            $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Dettaglio"), Mage::helper("adminhtml")->__("Dettaglio"));
            $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);
            $this->_addContent($this->getLayout()->createBlock("registrocorrispettivi/adminhtml_registrocorrispettivi_edit"))->_addLeft($this->getLayout()->createBlock("registrocorrispettivi/adminhtml_registrocorrispettivi_edit_tabs"));
            $this->renderLayout();
        }
        else {
            Mage::getSingleton("adminhtml/session")->addError(Mage::helper("registrocorrispettivi")->__("Item does not exist."));
            $this->_redirect("*/*/");
        }
    }

    public function newAction()
    {

          $this->_title($this->__("Nuovo Dettaglio"));

        $id   = $this->getRequest()->getParam("id");
        $model  = Mage::getModel("registrocorrispettivi/registrocorrispettivi")->load($id);

        $data = Mage::getSingleton("adminhtml/session")->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }

        Mage::register("registrocorrispettivi_data", $model);

        $this->loadLayout();
        $this->_setActiveMenu("sales/registrocorrispettivi");

        $this->getLayout()->getBlock("head")->setCanLoadExtJs(true);

        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Dettagli Corrispettivi"), Mage::helper("adminhtml")->__("Dettagli Corrispettivi"));
        $this->_addBreadcrumb(Mage::helper("adminhtml")->__("Dettaglio"), Mage::helper("adminhtml")->__("Dettaglio"));


        $this->_addContent($this->getLayout()->createBlock("registrocorrispettivi/adminhtml_registrocorrispettivi_edit"))->_addLeft($this->getLayout()->createBlock("registrocorrispettivi/adminhtml_registrocorrispettivi_edit_tabs"));

        $this->renderLayout();

    }

    public function saveAction()
    {

        $post_data=$this->getRequest()->getPost();


        if ($post_data) {
              $post_data = $this->_filterDates($post_data,array('data_comp'));
              try {

                $incrementId = $post_data['increment_id'];
                $order = Mage::getModel('sales/order')->loadByIncrementId($incrementId);
                if(!$order->getId()){
                    throw new Exception("Ordine non esistente");
                }
                $post_data['store_id'] = $order->getStoreId();
                $post_data['valuta']=$order->getOrderCurrencyCode();
                if($post_data['tipo']==Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO_NEG){
                    if($post_data['imponibile']>0){
                        $post_data['imponibile'] = -1*$post_data['imponibile'];
                    }
                    if($post_data['iva']>0){
                        $post_data['iva'] = -1*$post_data['iva'];
                    }
                }

                $post_data['imponibile_excl_tax'] = $post_data['imponibile']-$post_data['iva'];
                $labelPagamento = $order->getPayment()->getMethodInstance()->getTitle();
                $pagamento = $order->getPayment()->getMethodInstance()->getCode();
                $post_data['metodo_pagamento'] = $pagamento;
                $post_data['label_pagamento'] = $labelPagamento;
                $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi")
                    ->addData($post_data)
                    ->setId($this->getRequest()->getParam("id"))
                    ->save();
                $model->aggiornaCorrispettivo();

                Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Corrispettivo Salvato Correttamente"));
                Mage::getSingleton("adminhtml/session")->setRegistrocorrispettiviData(false);

                if ($this->getRequest()->getParam("back")) {
                    $this->_redirect("*/*/edit", array("id" => $model->getId()));
                    return;
                }
                $this->_redirect("*/*/");
                return;
            }
            catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
                Mage::getSingleton("adminhtml/session")->setRegistrocorrispettiviData($this->getRequest()->getPost());
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
                $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi");
                $model->setId($this->getRequest()->getParam("id"))->delete();
                $model->aggiornaCorrispettivo();

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
            $ids = $this->getRequest()->getPost('reg_ids', array());
            if(empty($ids)){
                throw new Exception("Selezionare almeno un dettaglio.") ;
            }
            $toRefresh = array();
            foreach ($ids as $id) {
                $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi")->load($id);
                if(!@$toRefresh[$model->getDataComp()]){
                    $toRefresh[$model->getDataComp()] = $model;
                }
                $model->setId($id)->delete();


            }
            foreach($toRefresh as $m){
                $m->aggiornaCorrispettivo();
            }
            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("I Dettagli sono stati cancellati"));
        }
        catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }

    public function massMoveAction()
    {
        try {
            $ids = $this->getRequest()->getPost('reg_ids', array());
            if(empty($ids)){
                throw new Exception("Selezionare almeno un dettaglio.") ;
            }
			$post_data=$this->getRequest()->getPost();
			$post_data = $this->_filterDates($post_data,array('nuova_data'));
       
            $value = $post_data['nuova_data'];
            $toRefresh = array();
            $model=Mage::getModel("registrocorrispettivi/registrocorrispettivi")->setDataComp($value);
            $toRefresh[$model->getDataComp()] = $model;
            foreach ($ids as $id) {
                $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi")->load($id);
                if(!@$toRefresh[$model->getDataComp()]){
                    $toRefresh[$model->getDataComp()] = $model;
                }
                $model->setDataComp($value)->save();


            }
            foreach($toRefresh as $k=>$m)
            {
                $m->setDataComp($k);
                $m->aggiornaCorrispettivo();
            }

            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("I dettagli sono stati spostati"));
        }
        catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('*/*/');
    }
    public function aggiungiOrdineAction(){
        if( $this->getRequest()->getParam("order_id") > 0 ) {
            try {

                $order = Mage::getModel('sales/order')->load($this->getRequest()->getParam("order_id"));
                $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi");
                $tipoDoc = Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO;
                $stati = array('canceled','closed','holded');
                if(!$model->isOrdineContabilizzato($order->getIncrementId())&& !in_array($order->getState(),$stati)){

                    $model->compilaRegistroCorrispettivi($order,$tipoDoc,$order->getIncrementId());
                $model->aggiornaCorrispettivo();
                Mage::getSingleton("adminhtml/session")->addSuccess("Corrispettivo Aggiunto");
                }else{
                    throw new Exception("Ordine già contabilizzato o in stato non consentito.");
                }
            } catch (Exception $e) {
                Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
            }
            $this->_redirect('adminhtml/sales_order/view',array('order_id'=>$this->getRequest()->getParam("order_id")));
        }


    }


    public function massContabilizzaAction(){
        try {
            $ids = $this->getRequest()->getPost('order_ids', array());
            if(empty($ids)){
                throw new Exception("Selezionare almeno un ordine.") ;
            }
           $post_data=$this->getRequest()->getPost();
			$post_data = $this->_filterDates($post_data,array('data_comp'));
       
            $value = $post_data['data_comp'];
            $contabilizzati = 0;
            $errors = array();
            foreach ($ids as $id) {
                $order = Mage::getModel('sales/order')->load($id);

                $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi");
                $stati = array('canceled','closed','holded');
                if(!$model->isOrdineContabilizzato($order->getIncrementId())&& !in_array($order->getState(),$stati)){
                    $tipoDoc = Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO;

                    $model->compilaRegistroCorrispettivi($order,$tipoDoc,$order->getIncrementId(),$value);
                    $contabilizzati++;
                }else{
                    if(in_array($order->getState(),$stati)){
                        $errors[] = $order->getIncrementId().' non contabilizzabile: stato non consentito.';
                    }else{
                        $errors[] = $order->getIncrementId().' già contabilizzato.';
                    }
                }


            }
            $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi");
            $model->setDataComp($value);
            $model->aggiornaCorrispettivo();

            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("{$contabilizzati} Ordini Contabilizzati"));
            if(!empty($errors)){
                Mage::getSingleton("adminhtml/session")->addError(implode("<br>",$errors));
            }
        }
        catch (Exception $e) {
            Mage::getSingleton("adminhtml/session")->addError($e->getMessage());
        }
        $this->_redirect('adminhtml/sales_order/index');
    }
	
	 public function massAddNoteAction()
    {
        try {
            $ids = $this->getRequest()->getPost('reg_ids', array());
            if(empty($ids)){
                throw new Exception("Selezionare almeno un dettaglio.") ;
            }
            $note = $this->getRequest()->getPost('note', '');

            foreach ($ids as $id) {
                $model = Mage::getModel("registrocorrispettivi/registrocorrispettivi");

                $model->setNote($note)->setId($id)->save();


            }


            Mage::getSingleton("adminhtml/session")->addSuccess(Mage::helper("adminhtml")->__("Le note sono state aggiornate"));
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
        $fileName   = 'dettagli.csv';
        $grid       = $this->getLayout()->createBlock('registrocorrispettivi/adminhtml_registrocorrispettivi_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'dettagli.xls';
        $grid       = $this->getLayout()->createBlock('registrocorrispettivi/adminhtml_registrocorrispettivi_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}
