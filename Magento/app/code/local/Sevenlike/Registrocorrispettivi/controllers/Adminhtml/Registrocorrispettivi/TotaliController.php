<?php

class Sevenlike_Registrocorrispettivi_Adminhtml_Registrocorrispettivi_TotaliController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()->_setActiveMenu("sales/registrocorrispettivi/registrocorrispettivi_totali")->_addBreadcrumb(Mage::helper("adminhtml")->__("Corrispettivi"),Mage::helper("adminhtml")->__("Registro Corrispettivi"));
        return $this;
    }
    public function indexAction()
    {
  $this->_title($this->__("Registro Corrispettivi"));

          $this->_initAction();
        $this->renderLayout();
    }


    /**
     * Export order grid to CSV format
     */
    public function exportCsvAction()
    {
        $fileName   = 'registrocorrispettivi.csv';
        $grid       = $this->getLayout()->createBlock('registrocorrispettivi/adminhtml_registrocorrispettivi_totali_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFile());
    }
    /**
     *  Export order grid to Excel XML format
     */
    public function exportExcelAction()
    {
        $fileName   = 'registrocorrispettivi.xls';
        $grid       = $this->getLayout()->createBlock('registrocorrispettivi/adminhtml_registrocorrispettivi_totali_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getExcelFile($fileName));
    }
}
