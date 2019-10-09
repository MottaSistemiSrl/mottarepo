<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Andrea Becchio
 * Date: 22/08/13
 * Time: 17.31
 * To change this template use File | Settings | File Templates.
 */
class Sevenlike_Registrocorrispettivi_Model_Mysql4_Registrocorrispettivi_Totali extends Mage_Core_Model_Mysql4_Abstract
{
    protected function _construct()
    {
        $this->_init("registrocorrispettivi/registrocorrispettivi_totali", "id");
    }

    public function aggiornaCorrispettivo($date){

        $table = $this->getMainTable();
        $writeAdapter = $this->_getWriteAdapter();
        $where = array(
                        "data_comp =  '{$date}'",
                        "tipo_doc = '".Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO."'"
        );

        $writeAdapter->delete($table, $where);

        $select = $this->_getInsertSelectCorr($date);

        $insert = $writeAdapter->insertFromSelect($select,$table,array(
         'data_comp','importo','iva','importo_netto','aliquota','valuta','p_iva','cod_paese','tipo_doc','n_doc','descrizione','metodo_pagamento','label_pagamento','discount_amount'));

        $writeAdapter->query($insert);

    }

    private function _getInsertSelectCorr($date){
        $dettaglioTable = Mage::getResourceModel('registrocorrispettivi/registrocorrispettivi')->getMainTable();
        $corr_name = 'dettaglio';
        $writeAdapter = $this->_getWriteAdapter();
       
        $select = $writeAdapter->select();
        $select->from(array($corr_name=>$dettaglioTable),array('data_comp'=>"data_comp",
            'importo'=>new Zend_Db_Expr("sum(imponibile)"),
            'iva'=>new Zend_Db_Expr("sum(iva)"),
            'importo_netto'=>new Zend_Db_Expr("sum(imponibile_excl_tax)"),
            'aliquota'=>"aliquota",
            'valuta'=>"valuta",
            'p_iva' =>new Zend_Db_Expr("''"),
            'cod_paese' =>new Zend_Db_Expr("'IT'"),
            'tipo_doc'=>new Zend_Db_Expr("'".Sevenlike_Registrocorrispettivi_Model_Tipodocumento::CORRISPETTIVO."'"),
            'n_doc'=>new Zend_Db_Expr("concat_ws('_','CORR',date_format(data_comp,'%e-%m-%Y'),round(aliquota,1))"),
            'descrizione'=>new Zend_Db_Expr("group_concat(distinct(increment_id) separator '; ')"),
            'metodo_pagamento'=>'metodo_pagamento',
            'label_pagamento'=>'label_pagamento',
            'discount_amount'=>'discount_amount',
        ))->where('data_comp = ?',$date)
            ->group(array('data_comp','valuta','aliquota','p_iva','metodo_pagamento','label_pagamento'));
      //  $insert = "insert into {$table} (data_comp,importo,iva,importo_netto,aliquota,valuta,p_iva,cod_paese,tipo_doc,n_doc,descrizione) ".$select->__toString();

        return $select;
    }

   
}
