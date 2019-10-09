<?php

class Amm_Product_Helper_Data extends Mage_Core_Helper_Abstract
{

  public function checkTable(){

    $resource = Mage::getSingleton('core/resource');

    $table1 = $resource->getTableName('amm_product');
    $table2 = $resource->getTableName('catalog_product_entity');

    $query1 = 'select 
          t1.id
        from 
          '.$table1.' as t1 
        LEFT JOIN 
          '.$table2.' as t2 
        ON 
          t1.id_product=t2.entity_id
        where
          ISNULL(t2.entity_id);
        ';

    $resultd = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query1);
    $queryd = '';
    if($resultd){  
      foreach($resultd as $d){
        $queryd .= " delete from ".$table1." where id=".$d['id'].";";
      }

      $resource->getConnection('core_write')->query($queryd);
    }

  }

  public function getIdProduct($id=0){

    $resource = Mage::getSingleton('core/resource');

    $table1 = $resource->getTableName('amm_product');
    $query1 = 'select 
          t1.id_product
        from 
          '.$table1.' as t1
        where
          t1.id='.$id.';
        ';
    foreach(Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query1) as $far){
      return $far['id_product'];
    }
  }

	public function deleteProduct($session=0, $id=0){

		$resource = Mage::getSingleton('core/resource');
		
		$table1 = $resource->getTableName('amm_product');
		$tables = array( 
			$resource->getTableName('catalog_product_entity'),
			$resource->getTableName('catalog_product_entity_datetime'),
			$resource->getTableName('catalog_product_entity_decimal'),
			$resource->getTableName('catalog_product_entity_int'),
			$resource->getTableName('catalog_product_entity_media_gallery'),
			$resource->getTableName('catalog_product_entity_text'),
			$resource->getTableName('catalog_product_entity_varchar'),
			$resource->getTableName('catalog_product_flat_1'),
			$resource->getTableName('catalog_product_index_eav'),
			$resource->getTableName('catalog_product_index_eav_idx'),
			$resource->getTableName('catalog_product_index_eav_tmp'),
			$resource->getTableName('catalog_product_index_price'),
			$resource->getTableName('catalog_product_index_price_idx'),
			$resource->getTableName('catalog_product_index_price_tmp')
		);

		$query = "delete from ".$table1." where id_customer=".$session." and id_product=".$id.";";
		foreach($tables as $ts){
			$query .= " delete from ".$ts." where entity_id=".$id.";";
		}

		//echo $query; exit();

		$resource->getConnection('core_write')->query($query);

		return true;

	}

	public function getAdditionalFieldsProduct($id=0,$entid=0){

		$resource = Mage::getSingleton('core/resource');

		$table1 = $resource->getTableName('eav_attribute_group');
		$table2 = $resource->getTableName('eav_entity_attribute');
		$table3 = $resource->getTableName('eav_attribute');
		$table4 = $resource->getTableName('catalog_product_entity_text');
		$table5 = $resource->getTableName('catalog_product_entity_varchar');
		$table6 = $resource->getTableName('catalog_product_entity_int');
    $table7 = $resource->getTableName('catalog_eav_attribute');

		$query1 = 'select 
          t1.attribute_group_id, 
          t1.attribute_group_name
        from 
          '.$table1.' as t1
        where
          t1.attribute_set_id='.$id.' and 
          t1.attribute_group_name not like "%Design%" and 
          t1.attribute_group_name not like "%Description%" and 
          t1.attribute_group_name not like "%General%" and 
          t1.attribute_group_name not like "%Gift Options%" and 
          t1.attribute_group_name not like "%Images%" and 
          t1.attribute_group_name not like "%Meta Information%" and 
          t1.attribute_group_name not like "%Prices%" and 
          t1.attribute_group_name not like "%Recurring Profile%" 
        order by t1.sort_order asc;
        ';

        $productsArray1 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query1);

        $query2 = 'select
          t2.entity_attribute_id, 
          t2.attribute_id, 
          t3.attribute_code, 
          t3.backend_type, 
          t3.frontend_input, 
          t3.frontend_label
        from 
          '.$table1.' as t1, 
          '.$table2.' as t2, 
          '.$table3.' as t3,
          '.$table7.' as t7
        where
          t1.attribute_set_id='.$id.' and 
          t1.attribute_group_name not like "%Design%" and 
          t1.attribute_group_name not like "%General%" and 
          t1.attribute_group_name not like "%Gift Options%" and 
          t1.attribute_group_name not like "%Images%" and 
          t1.attribute_group_name not like "%Meta Information%" and 
          t1.attribute_group_name not like "%Prices%" and 
          t1.attribute_group_name not like "%Recurring Profile%" and 
          t2.attribute_set_id=t1.attribute_set_id and 
          t2.attribute_group_id = t1.attribute_group_id and 
          t3.attribute_id = t2.attribute_id and 
          t7.attribute_id=t2.attribute_id and t7.apply_to like "%downloadable%" 
        order by t2.sort_order asc;
        ';

        $productsArray2 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query2);

        $query3 = 'select
          t4.attribute_id, 
          t4.value 
        from 
          '.$table1.' as t1, 
          '.$table2.' as t2, 
          '.$table3.' as t3, 
          '.$table4.' as t4,
          '.$table7.' as t7
        where
          t1.attribute_set_id='.$id.' and 
          t1.attribute_group_name not like "%Design%" and 
          t1.attribute_group_name not like "%General%" and 
          t1.attribute_group_name not like "%Gift Options%" and 
          t1.attribute_group_name not like "%Images%" and 
          t1.attribute_group_name not like "%Meta Information%" and 
          t1.attribute_group_name not like "%Prices%" and 
          t1.attribute_group_name not like "%Recurring Profile%" and 
          t2.attribute_set_id=t1.attribute_set_id and 
          t2.attribute_group_id = t1.attribute_group_id and 
          t3.attribute_id = t2.attribute_id and 
          t4.attribute_id = t2.attribute_id and 
          t4.entity_id = '.$entid.' and 
          t7.attribute_id=t2.attribute_id and t7.apply_to like "%downloadable%"  
        order by t2.sort_order asc;
        ';

        $productsArray3 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query3);

        $query4 = 'select
          t5.attribute_id, 
          t5.value 
        from 
          '.$table1.' as t1, 
          '.$table2.' as t2, 
          '.$table3.' as t3, 
          '.$table5.' as t5,
          '.$table7.' as t7
        where
          t1.attribute_set_id='.$id.' and 
          t1.attribute_group_name not like "%Design%" and 
          t1.attribute_group_name not like "%General%" and 
          t1.attribute_group_name not like "%Gift Options%" and 
          t1.attribute_group_name not like "%Images%" and 
          t1.attribute_group_name not like "%Meta Information%" and 
          t1.attribute_group_name not like "%Prices%" and 
          t1.attribute_group_name not like "%Recurring Profile%" and 
          t2.attribute_set_id=t1.attribute_set_id and 
          t2.attribute_group_id = t1.attribute_group_id and 
          t3.attribute_id = t2.attribute_id and 
          t5.attribute_id = t2.attribute_id and 
          t5.entity_id = '.$entid.' and 
          t7.attribute_id=t2.attribute_id and t7.apply_to like "%downloadable%" 
        order by t2.sort_order asc;
        ';

        $productsArray4 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query4);

        $query5 = 'select
          t6.attribute_id, 
          t6.value 
        from 
          '.$table1.' as t1, 
          '.$table2.' as t2, 
          '.$table3.' as t3, 
          '.$table6.' as t6,
          '.$table7.' as t7
        where
          t1.attribute_set_id='.$id.' and 
          t1.attribute_group_name not like "%Design%" and 
          t1.attribute_group_name not like "%General%" and 
          t1.attribute_group_name not like "%Gift Options%" and 
          t1.attribute_group_name not like "%Images%" and 
          t1.attribute_group_name not like "%Meta Information%" and 
          t1.attribute_group_name not like "%Prices%" and 
          t1.attribute_group_name not like "%Recurring Profile%" and 
          t2.attribute_set_id=t1.attribute_set_id and 
          t2.attribute_group_id = t1.attribute_group_id and 
          t3.attribute_id = t2.attribute_id and 
          t6.attribute_id = t2.attribute_id and 
          t6.entity_id = '.$entid.' and 
          t7.attribute_id=t2.attribute_id and t7.apply_to like "%downloadable%"
        order by t2.sort_order asc;
        ';

        $productsArray5 = Mage::getSingleton('core/resource')->getConnection('core_read')->fetchAll($query5);

        $arrayNot = array("Design","General","Gift Options","Images","Meta Information","Prices","Recurring Profile");

        $result = '{"attributes":[';
        $ir=0;
        if($productsArray1){
	        foreach($productsArray1 as $pa1){
	        	if(!in_array($pa1['attribute_group_name'], $arrayNot)){
	        		$result .= ($ir>0?',':'').'{"group_id":"'.$pa1['attribute_group_id'].'", "group_name":"'.$pa1['attribute_group_name'].'"';

	        			if($productsArray2){
	        				$ir2=0;
	        				$result .= ', "attribute_id":[';
	        					foreach($productsArray2 as $pa2){
	        						$value_textarea = '';
	        						if($productsArray3){
	        							foreach($productsArray3 as $pa3){
	        								if($pa3['attribute_id']==$pa2['attribute_id']){
	        									$value_textarea = $pa3['value'];
	        								}
	        							}
	        						}
	        						$value_text = '';
	        						if($productsArray4){
	        							foreach($productsArray4 as $pa4){
	        								if($pa4['attribute_id']==$pa2['attribute_id']){
	        									$value_text = $pa4['value'];
	        								}
	        							}
	        						}
	        						$value_boolean = '';
	        						if($productsArray5){
	        							foreach($productsArray5 as $pa5){
	        								if($pa5['attribute_id']==$pa2['attribute_id']){
	        									$value_boolean = $pa5['value'];
	        								}
	        							}
	        						}

	        						$result .= ($ir2>0?',':'').'{"id":"'.$pa2['attribute_id'].'", "code":"'.$pa2['attribute_code'].'", "type":"'.$pa2['backend_type'].'", "input":"'.$pa2['frontend_input'].'", "label":"'.$pa2['frontend_label'].'", "value":"'.($pa2['backend_type']=='text' && $pa2['frontend_input']=='textarea' ? $value_textarea : ($pa2['backend_type']=='varchar' && $pa2['frontend_input']=='text' ? $value_text : ($pa2['backend_type']=='int' && $pa2['frontend_input']=='boolean' ? $value_boolean : ''))).'"}';
	        						$ir2++;
	        					}
	        				$result .= ']';
	        			}
	        		$result .= '}';
	        		$ir++;
	        	}
	        }
	    }
        $result .= ']}';

		return $result;
	}

}

