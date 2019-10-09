<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 24/07/13
 * Time: 11.43
 * To change this template use File | Settings | File Templates.
 */
class Sevenlike_Registrocorrispettivi_Model_Observer
{
    public function addRegistroInvoice($observer){
        $invoice= $observer->getEvent()->getInvoice();
        $order = $invoice->getOrder();
        $zDataComp = $order->getCreatedAtStoreDate();
        $data_comp = $zDataComp->toString('YYYY-MM-dd');

        if(Mage::helper('registrocorrispettivi')->getModelloInserimento($order->getStoreId())==Sevenlike_Registrocorrispettivi_Model_Config_Inserimento::INVOICE){

            if($invoice->getState()== Mage_Sales_Model_Order_Invoice::STATE_PAID){
                $insertCorrispettivo  = true;
                if(Mage::helper('core')->isModuleEnabled('Sevenlike_Fatturazione')){
                    $insertCorrispettivo =  !$order->getBillingAddress()->getRichiestaFattura();
                }
                if($invoice->getAddCorrispettivo() || $insertCorrispettivo){
                    $tipoDoc = Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO;

                 
                        $model =Mage::getModel('registrocorrispettivi/registrocorrispettivi');
                        $model->compilaRegistroCorrispettivi($order,$tipoDoc,$order->getIncrementId(), $data_comp);

                    
                    $model->aggiornaCorrispettivo();
//                    $model =Mage::getModel('registrocorrispettivi/registrocorrispettivi');
//                    $model->registraSpeseAggiuntive($order,$tipoDoc);
//                    die('sad');

                }
            }
        }


    }
    public function addRegistroCreditmemo($observer){
        $creditmemo= $observer->getEvent()->getCreditmemo();
        $order = $creditmemo->getOrder();
        if($creditmemo->getState()== Mage_Sales_Model_Order_Creditmemo::STATE_REFUNDED){
            $insertCorrispettivo = true;
            if(Mage::helper('core')->isModuleEnabled('Sevenlike_Fatturazione')){
                $insertCorrispettivo =  !$order->getBillingAddress()->getRichiestaFattura();
            }
            if($creditmemo->getAddCorrispettivo()|| $insertCorrispettivo){
                $tipoDoc = Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO_NEG;

                      $model =Mage::getModel('registrocorrispettivi/registrocorrispettivi');
                    $model->compilaRegistroCorrispettivi($order,$tipoDoc,$order->getIncrementId());
                    $model->aggiornaCorrispettivo();
                


            }


        }

    }
    public function addRegistroShipping($observer){
        $shipping = $observer->getEvent()->getShipment();
        $order = $shipping->getOrder();
        if(Mage::helper('registrocorrispettivi')->getModelloInserimento($order->getStoreId())==Sevenlike_Registrocorrispettivi_Model_Config_Inserimento::SPEDIZIONE){

            //TODO: gestire attributo per modulo standalone

            if($shipping->getAddCorrispettivo()){

                $tipoDoc = Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO;
                $model =Mage::getModel('registrocorrispettivi/registrocorrispettivi');
                if(!$model->isOrdineContabilizzato($order->getIncrementId())){


                        $model =Mage::getModel('registrocorrispettivi/registrocorrispettivi');
                        $model->compilaRegistroCorrispettivi($order,$tipoDoc,$order->getIncrementId());
                        $model->aggiornaCorrispettivo();

                }
            }
        }
    }
    public function aggiornaCorrispettivi($schedule){
        Mage::log("Aggiorno corrispettivi crontab");
        $date = date('Y-m-d');
        $coll = Mage::getModel('registrocorrispettivi/registrocorrispettivi_totali')->getCollection()->addFieldToFilter('data_comp',$date);

        if($coll->getSize()==0){
            $calc = Mage::getSingleton('tax/calculation');
            $rates = $calc->getRatesForAllProductTaxClasses($calc->getRateRequest());
            $rates = array_values($rates);
            $rate = (float)$rates[0];
            $rate = round($rate,1);
            $totali = array('cod_paese'=>'IT',
                'data_comp'=>$date,
                'tipo_doc'=>Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO,
                'n_doc'=>'CORR_'.date('d-m-Y').$rate,
                'aliquota'=>$rate,
                'valuta'=>Mage::app()->getStore()->getCurrentCurrencyCode(),
                'importo'=>0,
                'iva'=>0,
                'importo_netto'=>0,
                'discount_amount'=>0,
                'descrizione'=>'Nessun ordine ricevuto'
            );
            $tot = Mage::getModel('registrocorrispettivi/registrocorrispettivi_totali')->addData($totali)->save();
        }

    }





}
