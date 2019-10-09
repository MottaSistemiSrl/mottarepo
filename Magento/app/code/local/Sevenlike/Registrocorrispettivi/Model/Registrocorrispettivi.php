<?php

class Sevenlike_Registrocorrispettivi_Model_Registrocorrispettivi extends Mage_Core_Model_Abstract
{
    protected function _construct(){

        $this->_init("registrocorrispettivi/registrocorrispettivi");

    }
    public function aggiornaCorrispettivo(){
        $date = $this->getDataComp();

        if($date){
            Mage::getResourceModel('registrocorrispettivi/registrocorrispettivi_totali')->aggiornaCorrispettivo($date);
        }
    }
    public function isOrdineContabilizzato($incrementId){
        $coll = $this->getCollection()->addFieldToFilter('increment_id',$incrementId);
        return $coll->getSize()>0;
    }
    public function compilaRegistroCorrispettivi($order,$tipo,$increment_id,$data_comp = null){
        //registro dei corrispettivi

        if(!$data_comp){
            $data_comp = date('Y-m-d');
        }
        $reg = array();
        $segno = 1;
        if($tipo == Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CREDIT_MEMO || $tipo == Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO_NEG){
            $segno = -1;
        }
        $pagamento = '';
        $labelPagamento = '';
        $useOrder = false;

        $labelPagamento = $order->getPayment()->getMethodInstance()->getTitle();
        $pagamento = $order->getPayment()->getMethodInstance()->getCode();
        $taxInfo = $order->getFullTaxInfo();

		$shipping = $order->getShippingAmount() + $order->getShippingTaxAmount();
		
		$subtotalInclTax = $shipping;
		
		$products = $order->getAllItems();
		foreach($products as $p)
        {
            if($p->getParentItemId()){
                continue;
            }
            if($p->getTaxPercent() == 22)
            {
                $subtotalInclTax += $p->getBaseRowTotalInclTax() - $p->getBaseDiscountAmount();

            }
        }
		
		
		
        $idCustomer = $order->getCustomerId();
        $customer_data = Mage::getModel('customer/customer')->load($idCustomer);

        $attribute = $customer_data->getResource()->getAttribute('regione');
        $regione_value = $attribute ->getFrontend()->getValue($customer_data);

        $attribute = $customer_data->getResource()->getAttribute('sevenlike_provincia');
        $provincia_value = $attribute ->getFrontend()->getValue($customer_data);

        foreach ($taxInfo as $tax){
            if($tax['percent'] == 0){
                continue;
            }
            
            //$subtotalInclTax += $shipping;
			$subtotalExclTax = $subtotalInclTax - $tax['amount'];
            $data = array();
            $data['increment_id']=$increment_id;
            $data['store_id']=$order->getStoreId();
            $data['imponibile']=$segno*$subtotalInclTax;
            $data['imponibile_excl_tax']=($segno*$subtotalExclTax);
            $data['iva']=$segno*$tax['amount'];
            $data['aliquota']=$tax['percent'];
            $data['valuta']=$order->getOrderCurrencyCode();
            $data['tipo'] = $tipo;
            $data['data_comp']= $data_comp;
            $data['metodo_pagamento'] = $pagamento;
            $data['label_pagamento'] = $labelPagamento;
            $data['provincia'] = $provincia_value;
            $data['regione'] = $regione_value;
            //inseriamo anche regione e provincia

            $this->addData($data)->save();
        }
        $subtotalInclTax = 0;

        

        $discountAmount = 0;
        foreach($products as $p)
        {
            if($p->getParentItemId()){
                continue;
            }
            if($p->getTaxPercent() == 0)
            {
                $subtotalInclTax += $p->getBaseRowTotalInclTax() - $p->getBaseDiscountAmount();

            }
        }

      if($subtotalInclTax>0) {

          $data = array();
          $data['increment_id'] = $increment_id;
          $data['store_id'] = $order->getStoreId();
          $data['imponibile'] = $segno * $subtotalInclTax;
          $data['imponibile_excl_tax'] = ($segno *$subtotalInclTax);
          $data['iva'] = 0;
          $data['aliquota'] = 0;
          $data['valuta'] = $order->getOrderCurrencyCode();
          $data['tipo'] = $tipo;
          $data['data_comp'] = $data_comp;
          $data['metodo_pagamento'] = $pagamento;
          $data['label_pagamento'] = $labelPagamento;
          $data['discount_amount'] = $discountAmount;
          $data['provincia'] = $provincia_value;
          $data['regione'] = $regione_value;
		  
          //inseriamo anche regione e provincia
          $this->setId(null);
          $this->addData($data)->save();
      }

        return $this;
    }

}
	 